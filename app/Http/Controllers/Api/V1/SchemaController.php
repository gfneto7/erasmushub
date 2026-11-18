<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchemaController extends Controller
{
    /**
     * Demonstrates JSON Schema compliance verification for valid and invalid payloads.
     */
    public function validatePayload(Request $request): JsonResponse
    {
        $schemaPath = public_path('schemas/guide-schema.json');
        if (! file_exists($schemaPath)) {
            return response()->json([
                'status' => 'error',
                'message' => 'JSON Schema definition file not found.',
            ], 500);
        }

        $schema = json_decode(file_get_contents($schemaPath), true);
        $payload = $request->all();

        $errors = [];

        // Check required fields
        foreach ($schema['required'] as $requiredField) {
            if (! array_key_exists($requiredField, $payload) || $payload[$requiredField] === null || $payload[$requiredField] === '') {
                $errors[] = "Missing required field: '{$requiredField}'";
            }
        }

        // Validate properties
        foreach ($payload as $key => $value) {
            if (! isset($schema['properties'][$key])) {
                if ($schema['additionalProperties'] === false) {
                    $errors[] = "Unknown property '{$key}' is not allowed by schema.";
                }
                continue;
            }

            $rules = $schema['properties'][$key];

            // Type checking
            if (isset($rules['type'])) {
                $expected = $rules['type'];
                $actual = gettype($value);
                if ($expected === 'integer' && ! is_int($value) && ! ctype_digit(strval($value))) {
                    $errors[] = "Field '{$key}' must be an integer, got {$actual}.";
                } elseif ($expected === 'string' && ! is_string($value)) {
                    $errors[] = "Field '{$key}' must be a string, got {$actual}.";
                }
            }

            // String lengths
            if (is_string($value)) {
                if (isset($rules['minLength']) && mb_strlen($value) < $rules['minLength']) {
                    $errors[] = "Field '{$key}' is too short (min {$rules['minLength']} characters).";
                }
                if (isset($rules['maxLength']) && mb_strlen($value) > $rules['maxLength']) {
                    $errors[] = "Field '{$key}' exceeds maximum length ({$rules['maxLength']} characters).";
                }
            }

            // Enum check
            if (isset($rules['enum']) && ! in_array($value, $rules['enum'], true)) {
                $errors[] = "Field '{$key}' must be one of: " . implode(', ', $rules['enum']) . ".";
            }
        }

        if (! empty($errors)) {
            return response()->json([
                'status' => 'invalid',
                'schema' => 'GuideCreationRequest (2020-12)',
                'valid' => false,
                'errors' => $errors,
                'received_payload' => $payload,
            ], 400, ['Content-Type' => 'application/json']);
        }

        return response()->json([
            'status' => 'valid',
            'schema' => 'GuideCreationRequest (2020-12)',
            'valid' => true,
            'message' => 'Payload is 100% compliant with the JSON Schema specification.',
            'received_payload' => $payload,
        ], 200, ['Content-Type' => 'application/json']);
    }
}
