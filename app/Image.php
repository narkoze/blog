<?php

namespace Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{

    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getImagesAttribute()
    {
        return [
            'original' => Storage::disk('public')->url("image/original/{$this->filename}"),
            'medium' => Storage::disk('public')->url("image/medium/{$this->filename}"),
            'small' => Storage::disk('public')->url("image/small/{$this->filename}"),
        ];
    }

    public function getDimensionsAttribute()
    {
        if ($this->width and $this->height) {
            return "$this->width × $this->height";
        }
        return '-';
    }
}
