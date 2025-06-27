<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\CompanyMember;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CompanyInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected CompanyMember $invitation,
        protected Company $company
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've been invited to join {$this->company->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.company_invitation',
            with: [
                'invitation' => $this->invitation,
                'company' => $this->company,
                'invitationUrl' => $this->getInvitationUrl(),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function getInvitationUrl(): string
    {
        // If user exists, direct them to login and accept
        if ($this->invitation->user_id) {
            return route('dashboard.companies.members.accept', $this->company);
        }
        
        // If user doesn't exist, direct them to register with email pre-filled
        return route('register', ['email' => $this->invitation->email, 'company_invite' => $this->company->id]);
    }
}
