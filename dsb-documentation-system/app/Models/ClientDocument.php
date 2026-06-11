<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientDocument extends Model
{
    protected $fillable = [
        'client_id',
        'original_name',
        'stored_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Return a human-readable file size string.
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1)    . ' KB';
        return $bytes . ' B';
    }

    /**
     * Font Awesome icon class based on mime type.
     */
    public function getIconClassAttribute(): string
    {
        $mime = $this->mime_type ?? '';

        if (str_contains($mime, 'pdf'))   return 'fa-file-pdf text-red-500';
        if (str_contains($mime, 'image')) return 'fa-file-image text-green-500';
        if (str_contains($mime, 'word') || str_contains($mime, 'document')) return 'fa-file-word text-blue-500';
        if (str_contains($mime, 'sheet') || str_contains($mime, 'excel'))   return 'fa-file-excel text-green-600';
        return 'fa-file text-gray-500';
    }
}