<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = ['guide_id', 'path', 'mime_type', 'size', 'alt_text'];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }
}