<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'title',
        'category_id',
        'file_path',
        'original_name',
        'status',
        'uploaded_by',
        'approved_by',
        'approved_at',
        'approval_note',
        'user_id',
        'from_status',
        'to_status',
        'note'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const PRIVATE_DISK = 'local';
    const PUBLIC_DISK = 'public';

    public function getPreviewUrl(): string
    {
        return route('documents.files.show', $this);
    }

    public function getDownloadUrl(): string
    {
        return route('documents.files.download', $this);
    }

    public function resolveStorageDisk(): string
    {
        $path = (string) $this->file_path;

        if ($path !== '' && Storage::disk(self::PRIVATE_DISK)->exists($path)) {
            return self::PRIVATE_DISK;
        }

        if ($path !== '' && Storage::disk(self::PUBLIC_DISK)->exists($path)) {
            return self::PUBLIC_DISK;
        }

        return self::PRIVATE_DISK;
    }

    /* ================= RELATIONS ================= */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(DocumentHistory::class);
    }

    public function legacyMap(): HasOne
    {
        return $this->hasOne(LegacyDocumentMap::class);
    }

    /* ================= STATUS HELPERS ================= */

    public function isPending(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /* ================= QUERY SCOPES ================= */

    public function scopeApproved(Builder $query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeSubmitted(Builder $query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }
}
