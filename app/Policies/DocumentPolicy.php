<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Document $document): bool
    {
        if ($user->hasRole('Admin') || $user->hasRole('Approver')) {
            return true;
        }

        if ($user->hasRole('Uploader')) {
            return $document->uploaded_by === $user->id;
        }

        if ($user->hasRole('Viewer')) {
            return $document->isApproved();
        }

        return false;
    }

    public function update(User $user, Document $document): bool
    {
        return $user->hasRole('Admin') ||
            ($user->hasRole('Uploader') && $document->uploaded_by === $user->id);
    }

    public function approve(User $user): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Approver');
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->hasRole('Admin');
    }
}
