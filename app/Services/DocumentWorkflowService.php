<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\{
    DocumentSubmittedNotification,
    DocumentApprovedNotification,
    DocumentRejectedNotification
};
use Throwable;

class DocumentWorkflowService
{
    /**
     * @param Document $document
     * @param User $actor
     * @return void
     * @throws Throwable
     */
    public function submit(Document $document, User $actor): void
    {
        DB::transaction(function () use ($document, $actor) {

            $document->update([
                'status' => Document::STATUS_SUBMITTED,
            ]);

            $document->histories()->create([
                'user_id' => $actor->id,
                'from_status' => Document::STATUS_DRAFT,
                'to_status' => Document::STATUS_SUBMITTED,
            ]);
            $approvers = User::all()
                ->filter(fn ($user) => $user->can('approve', $document));
            // Email all approvers

            Notification::send(
                $approvers,
                new DocumentSubmittedNotification($document)
            );
        });
    }

    /**
     * @param Document $document
     * @param User $actor
     * @return void
     * @throws Throwable
     */
    public function approve(Document $document, User $actor): void
    {
        DB::transaction(function () use ($document, $actor) {

            $document->update([
                'status' => Document::STATUS_APPROVED,
                'approved_by' => $actor->id,
                'approved_at' => now(),
            ]);

            $document->histories()->create([
                'user_id' => $actor->id,
                'from_status' => Document::STATUS_SUBMITTED,
                'to_status' => Document::STATUS_APPROVED,
            ]);

            // Email uploader
            $document->uploader->notify(
                new DocumentApprovedNotification($document)
            );
        });
    }

    /**
     * @param Document $document
     * @param User $actor
     * @param string $note
     * @return void
     * @throws Throwable
     */
    public function reject(Document $document, User $actor, string $note): void
    {
        DB::transaction(function () use ($document, $actor, $note) {

            $document->update([
                'status' => Document::STATUS_REJECTED,
                'approval_note' => $note,
            ]);

            $document->histories()->create([
                'user_id' => $actor->id,
                'from_status' => Document::STATUS_SUBMITTED,
                'to_status' => Document::STATUS_REJECTED,
                'note' => $note,
            ]);

            $document->uploader->notify(
                new DocumentRejectedNotification($document, $note)
            );
        });
    }
}
