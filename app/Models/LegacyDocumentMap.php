<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegacyDocumentMap extends Model
{
    protected $fillable = [
        'source',
        'legacy_table',
        'legacy_id',
        'document_id',
        'legacy_file_url',
        'legacy_payload',
        'imported_at',
    ];

    protected function casts(): array
    {
        return [
            'legacy_payload' => 'array',
            'imported_at' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
