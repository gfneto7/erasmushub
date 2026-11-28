<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Guide;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ErasmusHubTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test public home page loads with HTTP 200.
     */
    public function test_home_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ErasmusHub');
    }

    /**
     * Test about page loads.
     */
    public function test_about_page_loads(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('Open-Meteo');
    }

    /**
     * Test language switcher switches locale.
     */
    public function test_locale_switch_updates_session(): void
    {
        $response = $this->get('/locale/pt');
        $response->assertSessionHas('locale', 'pt');

        $responseHr = $this->get('/locale/hr');
        $responseHr->assertSessionHas('locale', 'hr');

        $responseEn = $this->get('/locale/en');
        $responseEn->assertSessionHas('locale', 'en');
    }

    /**
     * Test public guides index returns 200.
     */
    public function test_guides_index_loads(): void
    {
        $response = $this->get('/guides');
        $response->assertStatus(200);
    }

    /**
     * Test draft guides cannot be viewed by visitors (Section 5 & 6 requirement).
     */
    public function test_draft_guides_cannot_be_viewed_by_visitors(): void
    {
        $draftGuide = Guide::where('status', 'draft')->first();
        if ($draftGuide) {
            $response = $this->get('/guides/' . $draftGuide->slug);
            $response->assertStatus(403);
        }
    }

    /**
     * Test that inactive users are immediately logged out (Section 6 requirement).
     */
    public function test_inactive_user_is_logged_out_by_middleware(): void
    {
        $inactiveUser = User::where('is_active', false)->first();
        if ($inactiveUser) {
            $response = $this->actingAs($inactiveUser)->get('/dashboard');
            $response->assertRedirect('/login');
            $this->assertGuest();
        }
    }

    /**
     * Test category cannot be deleted if it contains associated guides (Section 5 requirement).
     */
    public function test_category_cannot_be_deleted_when_it_has_guides(): void
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->first();
        $categoryWithGuides = Category::whereHas('guides')->first();

        if ($admin && $categoryWithGuides) {
            $response = $this->actingAs($admin)->delete('/admin/categories/' . $categoryWithGuides->id);
            $response->assertSessionHas('error');
            $this->assertDatabaseHas('categories', ['id' => $categoryWithGuides->id]);
        }
    }

    /**
     * Test editor cannot update another author's guide (Section 6 & 13 requirement).
     */
    public function test_editor_cannot_update_another_editors_guide(): void
    {
        $editor1 = User::where('email', 'editor1@example.com')->first();
        $guideByEditor2 = Guide::whereHas('author', fn ($q) => $q->where('email', 'editor2@example.com'))->first();

        if ($editor1 && $guideByEditor2) {
            $response = $this->actingAs($editor1)->get('/admin/guides/' . $guideByEditor2->id . '/edit');
            $response->assertStatus(403);
        }
    }

    /**
     * Test API v1 guides endpoint returns 200 with JSON and pagination (Section 8 requirement).
     */
    public function test_api_v1_guides_endpoint(): void
    {
        $response = $this->getJson('/api/v1/guides');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data',
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            'links',
        ]);
    }

    /**
     * Test API v1 categories endpoint returns 200 with JSON.
     */
    public function test_api_v1_categories_endpoint(): void
    {
        $response = $this->getJson('/api/v1/categories');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data',
        ]);
    }

    /**
     * Test JSON Schema validation endpoint with valid and invalid payloads (Section 8 requirement).
     */
    public function test_json_schema_validation(): void
    {
        // 1. Valid payload
        $validPayload = [
            'title'       => 'Complete Guide to Zagreb Public Transport',
            'summary'     => 'Everything you need to know about trams and buses in Zagreb.',
            'content'     => '<p>Zagreb has an extensive tram network operated by ZET.</p>',
            'category_id' => 1,
            'city'        => 'Zagreb',
            'status'      => 'published',
        ];

        $responseValid = $this->postJson('/api/v1/validate-guide-schema', $validPayload);
        $responseValid->assertStatus(200);
        $responseValid->assertJson([
            'status' => 'valid',
            'valid'  => true,
        ]);

        // 2. Invalid payload (missing summary, title too short, non-existent enum status)
        $invalidPayload = [
            'title'   => 'No',
            'content' => 'Short',
            'status'  => 'invalid_status',
        ];

        $responseInvalid = $this->postJson('/api/v1/validate-guide-schema', $invalidPayload);
        $responseInvalid->assertStatus(400);
        $responseInvalid->assertJson([
            'status' => 'invalid',
            'valid'  => false,
        ]);
        $this->assertNotEmpty($responseInvalid->json('errors'));
    }

    /**
     * Test API login endpoint returns a Sanctum token (Section 8 & 13 requirement).
     */
    public function test_api_login_returns_token(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email'       => 'editor1@example.com',
            'password'    => 'password',
            'device_name' => 'PHPUnit',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'token',
            'user' => ['id', 'name', 'email', 'role'],
        ]);
        $this->assertEquals('success', $response->json('status'));
    }

    /**
     * Test API login with wrong credentials returns 422 (Section 13 requirement).
     */
    public function test_api_login_with_wrong_credentials_fails(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email'    => 'editor1@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test API login for inactive user returns 403 (Section 6 requirement).
     */
    public function test_api_login_inactive_user_is_forbidden(): void
    {
        $response = $this->postJson('/api/v1/login', [
            'email'    => 'inactive@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that uploading a non-image file type is rejected (Section 10 requirement).
     */
    public function test_image_upload_rejects_invalid_mime_type(): void
    {
        Storage::fake('public');

        $editor = User::where('email', 'editor1@example.com')->first();
        $category = Category::first();

        // Attempt to upload a PHP file disguised as an image
        $fakePhpFile = UploadedFile::fake()->create('malicious.php', 100, 'application/x-php');

        $response = $this->actingAs($editor)->post('/admin/guides', [
            '_token'      => csrf_token(),
            'title'       => 'Test Guide',
            'summary'     => 'A test guide summary for validation.',
            'content'     => '<p>Content here.</p>',
            'category_id' => $category->id,
            'status'      => 'draft',
            'image'       => $fakePhpFile,
        ]);

        // Should redirect back with validation error on image field
        $response->assertSessionHasErrors(['image']);
    }

    /**
     * Test that an admin cannot change their own role (Section 6 & 13 requirement).
     * Prevents privilege escalation and accidental self-lockout.
     */
    public function test_admin_cannot_change_own_role(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $registeredRole = Role::where('name', 'registered')->first();

        // Attempt to demote themselves to registered user
        $response = $this->actingAs($admin)->patch('/admin/users/' . $admin->id, [
            'role_id' => $registeredRole->id,
        ]);

        // Admin should NOT be able to change their own role
        $response->assertSessionHas('error');

        // Verify role unchanged in DB
        $this->assertDatabaseHas('users', [
            'id'      => $admin->id,
            'role_id' => $admin->role_id, // still admin role
        ]);
    }

    /**
     * Test API returns 404 for draft guide (unpublished guides hidden from API).
     */
    public function test_api_draft_guide_returns_404(): void
    {
        $draftGuide = Guide::where('status', 'draft')->first();

        if ($draftGuide) {
            $response = $this->getJson('/api/v1/guides/' . $draftGuide->id);
            $response->assertStatus(404);
        }
    }

    /**
     * Test public destinations page loads with status 200 and displays EU cities.
     */
    public function test_destinations_page_loads(): void
    {
        $response = $this->get('/destinations');
        $response->assertStatus(200);
        $response->assertSee('Erasmus Destinations');
        $response->assertSee('Lisboa');
    }

    /**
     * Test destinations page with specific city selection (e.g. Zagreb).
     */
    public function test_destinations_page_with_city_filter(): void
    {
        $response = $this->get('/destinations?city=zagreb');
        $response->assertStatus(200);
        $response->assertSee('Zagreb');
        $response->assertSee(__('Croácia'));
    }

    /**
     * Test API destinations list endpoint returns 200 with countries and cities.
     */
    public function test_api_destinations_index(): void
    {
        $response = $this->getJson('/api/v1/destinations');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => ['id', 'name', 'code', 'flag_emoji', 'cities'],
            ],
        ]);
    }

    /**
     * Test API single destination weather endpoint returns 200 with forecast.
     */
    public function test_api_destination_show_with_weather(): void
    {
        $response = $this->getJson('/api/v1/destinations/zagreb');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'city' => ['id', 'name', 'slug', 'latitude', 'longitude'],
                'weather',
            ],
        ]);
    }
}
