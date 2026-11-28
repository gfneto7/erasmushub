<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guide extends Model
{
    // author_id não está aqui de propósito: é sempre definido pelo servidor
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'category_id',
        'city_id',
        'city',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function destinationCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function image(): HasOne
    {
        return $this->hasOne(Image::class);
    }

    /**
     * URL completa da imagem do guia (comportamento idêntico ao FunShirt).
     * 1. Se tem imagem carregada no disco public pelo CMS, usa essa.
     * 2. Se tem cidade associada e existe foto local do monumento, usa essa.
     * 3. Caso contrário, devolve a imagem padrão de guia.
     */
    public function getImageFullUrlAttribute(): string
    {
        if ($this->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->image->path)) {
            return asset('storage/' . $this->image->path);
        }

        if ($this->city) {
            $citySlug = \Illuminate\Support\Str::slug($this->city);
            if (file_exists(public_path("images/cities/{$citySlug}.jpg"))) {
                return asset("images/cities/{$citySlug}.jpg");
            }
        }

        return asset('images/default-guide.jpg');
    }

    // Só guias publicados (usado no site público e na API)
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Auto-traduz o título e resumo do guia para as restantes línguas
     * sempre que um novo guia é criado ou atualizado.
     */
    protected static function booted(): void
    {
        static::saved(function (Guide $guide) {
            try {
                \App\Services\AutoTranslationService::translateGuide($guide);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("AutoTranslation on Guide saved: {$e->getMessage()}");
            }
        });
    }
}