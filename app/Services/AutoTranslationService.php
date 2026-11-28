<?php

namespace App\Services;

use App\Models\Guide;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AutoTranslationService
{
    /**
     * Supported application locales.
     */
    public const SUPPORTED_LOCALES = ['pt', 'en', 'hr'];

    /**
     * Translates a single text from source language to target language using the MyMemory API.
     * Handles timeout (4s), decodes HTML entities, and provides graceful fallback.
     */
    public static function translate(string $text, string $targetLang, string $sourceLang = 'pt'): string
    {
        $text = trim($text);
        if (empty($text) || $targetLang === $sourceLang) {
            return $text;
        }

        try {
            $response = Http::timeout(4)
                ->get('https://api.mymemory.translated.net/get', [
                    'q' => $text,
                    'langpair' => "{$sourceLang}|{$targetLang}",
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['responseData']['translatedText'])) {
                    $translated = html_entity_decode($data['responseData']['translatedText'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    // Ensure MyMemory didn't return an error message
                    if (!str_contains($translated, 'MYMEMORY WARNING') && !str_contains($translated, 'INVALID TARGET LANGUAGE')) {
                        return trim($translated);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("AutoTranslationService translation failed: {$e->getMessage()}");
        }

        return $text;
    }

    /**
     * Translate guide title and summary across all supported languages
     * and persist new translations into lang/{locale}.json.
     */
    public static function translateGuide(Guide $guide, ?string $sourceLocale = null): void
    {
        $sourceLocale = $sourceLocale ?: app()->getLocale();
        if (!in_array($sourceLocale, self::SUPPORTED_LOCALES)) {
            $sourceLocale = 'pt';
        }

        $fields = array_filter([
            'title' => $guide->title,
            'summary' => $guide->summary,
        ]);

        if (empty($fields)) {
            return;
        }

        foreach (self::SUPPORTED_LOCALES as $targetLocale) {
            $filePath = base_path("lang/{$targetLocale}.json");
            $json = [];
            if (file_exists($filePath)) {
                $json = json_decode(file_get_contents($filePath), true) ?: [];
            }

            $hasUpdates = false;

            foreach ($fields as $text) {
                if ($targetLocale === $sourceLocale) {
                    if (!isset($json[$text])) {
                        $json[$text] = $text;
                        $hasUpdates = true;
                    }
                } else {
                    // If translation doesn't exist yet or is identical to source
                    if (!isset($json[$text]) || $json[$text] === $text) {
                        $translated = self::translate($text, $targetLocale, $sourceLocale);
                        $json[$text] = $translated;
                        $hasUpdates = true;
                    }
                }
            }

            if ($hasUpdates) {
                file_put_contents(
                    $filePath,
                    json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                );
            }
        }
    }
}
