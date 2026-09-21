<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlertNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $alertData;
    public $isReminder; // <-- TAMBAHKAN INI

    public function __construct($alertData, $isReminder = false)
    {
        $this->alertData = $alertData;
        $this->isReminder = $isReminder;
    }

    public function envelope(): Envelope
    {
        $prefix = $this->isReminder ? '🔔 REMINDER: ' : '🚨 ';
        $subject = $prefix . "[{$this->alertData['level']}] {$this->alertData['parameter']} Alert at {$this->alertData['location']}";
        
        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.alert-notification');
    }

    public function attachments(): array { return []; }
}