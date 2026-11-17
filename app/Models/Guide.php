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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function image(): HasOne
    {
        return $this->hasOne(Image::class);
    }

    // Só guias publicados (usado no site público e na API)
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}