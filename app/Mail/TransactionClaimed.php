<?php

namespace App\Mail;

use App\Models\PointsTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionClaimed extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @param PointsTransaction $pointsTransaction
     */
    public function __construct(private readonly PointsTransaction $pointsTransaction)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Transaction Claimed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $text = "Congratulations! Your transaction {$this->pointsTransaction->uuid}  has been claimed.";

        return new Content(
            view: 'emails.transaction-claimed',
            with: ['text' => $text],
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
