<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\OrderCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendOrderEmails
{
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
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        Mail::to($order->user->email)
            ->send(new OrderCreatedMail($order));

        Mail::to(config('mail.admin_mail'))
            ->send(new OrderCreatedMail($order));
    }
}
