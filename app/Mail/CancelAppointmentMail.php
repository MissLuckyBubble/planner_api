<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CancelAppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public Business $business;
    public Customer $customer;

    public function __construct($customer, $business, $appointment)
    {
        $this->customer = $customer;
        $this->business = $business;
        $this->appointment = $appointment;
        $this->address = $business->address;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Отказан час за посещение',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cancel_appointment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
