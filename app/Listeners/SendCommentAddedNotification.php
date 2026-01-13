<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Mail\CommentAddedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendCommentAddedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentAdded $event): void
    {
        // Don't send notification if the comment is internal
        if ($event->comment->is_internal) {
            return;
        }

        // Don't send notification to the comment author
        $ticketOwner = $event->comment->ticket->user;
        if ($ticketOwner->id === $event->comment->user_id) {
            return;
        }

        // Send email notification to the ticket owner
        Mail::to($ticketOwner->email)
            ->send(new CommentAddedMail($event->comment));
    }

    /**
     * Handle a job failure.
     */
    public function failed(CommentAdded $event, \Throwable $exception): void
    {
        // Log the failure or handle it appropriately
        \Log::error('Failed to send comment added notification', [
            'comment_id' => $event->comment->id,
            'ticket_id' => $event->comment->ticket_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
