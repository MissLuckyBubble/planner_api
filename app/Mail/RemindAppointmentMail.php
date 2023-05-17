<?php

namespace App\Mail;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RemindAppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Customer $customer;
    public Business $business;
    public Address $address;
    public Appointment $appointment;
    public string $infoday;
    public function __construct($customer, $business, $appointment)
    {
        $this->customer = $customer;
        $this->business = $business;
        $this->appointment = $appointment;
        $this->address = $business->address;
        if(Carbon::parse($appointment->date)->format('d.m.Y') === Carbon::today()->format('d.m.Y')){
            $this->infoday = 'днес';
        }else  $this->infoday = 'утре';
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Напомняме ви за предстоящия ви час ' . $this->infoday . ' при ' . $this->business->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.remind_appointment',
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
