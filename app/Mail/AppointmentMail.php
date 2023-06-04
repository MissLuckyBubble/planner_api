<?php

namespace App\Mail;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\Customer;
use App\Models\GroupAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Customer $customer;
    public Business $business;
    public Address $address;
    public $appointment;

    public function __construct($customer, $business, $appointment)
    {
        $this->customer = $customer;
        $this->business = $business;
        $this->address = $business->address;

        $this->appointment = $appointment;

    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Успешно запазен час за вас.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.appointment',
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
