<?php

namespace App\Listeners;

use App\Events\TicketStatusChanged;
use App\Mail\TicketStatusChangedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTicketStatusChangedNotification implements ShouldQueue
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
    public function handle(TicketStatusChanged $event): void
    {
        // Send email notification to the ticket owner
        Mail::to($event->ticket->user->email)
            ->send(new TicketStatusChangedMail(
                $event->ticket,
                $event->oldStatus,
                $event->newStatus
            ));
    }

    /**
     * Handle a job failure.
     */
    public function failed(TicketStatusChanged $event, \Throwable $exception): void
    {
        // Log the failure or handle it appropriately
        \Log::error('Failed to send ticket status changed notification', [
            'ticket_id' => $event->ticket->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
