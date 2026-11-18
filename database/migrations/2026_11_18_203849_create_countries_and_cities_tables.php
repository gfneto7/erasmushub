<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 5)->unique(); // PT, ES, HR, FR, DE, IT...
            $table->string('flag_emoji', 10)->default('🇪🇺');
            $table->string('currency', 10)->default('EUR');
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('main_university')->nullable();
            $table->text('description')->nullable();
            $table->string('cost_of_living', 20)->default('Medium'); // Low, Medium, High
            $table->timestamps();
        });

        Schema::table('guides', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('category_id')->constrained('cities')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
        });

        Schema::dropIfExists('cities');
        Schema::dropIfExists('countries');
    }
};
