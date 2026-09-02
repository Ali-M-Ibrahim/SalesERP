<?php

namespace App\Mail;

use App\Models\CustomerSatisfactionInvitation;
use App\Models\CustomerSatisfactionSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerSatisfactionMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public CustomerSatisfactionInvitation $invitation;

    public CustomerSatisfactionSetting|null $settings;


    public function __construct(CustomerSatisfactionInvitation $invitation)
    {
        $this->invitation = $invitation;

        $this->settings = CustomerSatisfactionSetting::first();
    }


    public function build()
    {
        return $this->subject($this->settings?->email_subject ?? 'Thank you for your time')->view('emails.customer-satisfaction');
    }
}
