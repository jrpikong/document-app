<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function getPreviewUrl(): string
    {
        return Storage::temporaryUrl(
            $this->file_path,
            now()->addMinutes(10)
        );
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
