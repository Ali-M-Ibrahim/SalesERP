<?php

namespace App\Jobs;

use App\Mail\CustomerSatisfactionMail;
use App\Models\CustomerSatisfactionInvitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendCustomerSatisfactionEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $invitationId)
    {
    }

    public function handle(): void
    {
        $invitation = CustomerSatisfactionInvitation::with(['visit.customer', 'visit.salesRep', 'visit.visitPurpose', 'visit.visitSamples.sample',])->find($this->invitationId);


        if (!$invitation) {
            return;
        }


        /*
         * Already sent.
         */
        if ($invitation->sent_at) {
            return;
        }


        /*
         * Invitation expired.
         */
        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            return;
        }


        if (!$invitation->email) {
            return;
        }


        Mail::to($invitation->email)->send(new CustomerSatisfactionMail($invitation));


        $invitation->update(['sent_at' => now(),]);
    }
}
