<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendDonationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $medicineName;
    public $medicineQty;
    public $medicinePrice;

    /**
     * Create a new message instance.
     */
    public function __construct($otp,$medicineName,$medicineQty,$medicinePrice)
    {
        $this->otp = $otp;
        $this->medicineName = $medicineName;
        $this->medicineQty = $medicineQty;
        $this->medicinePrice = $medicinePrice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Donation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'SendDonationView',
            with: [
                'otp' => $this->otp,
                'medicineName' => $this->medicineName,
                'medicineQty' => $this->medicineQty,
                'medicinePrice' => $this->medicinePrice,
            ],
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
