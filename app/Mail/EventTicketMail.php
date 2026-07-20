<?php

namespace App\Mail;

use App\Models\Transaction;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public ?string $qrBase64     = null;
    public ?string $posterBase64 = null;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function build(): static
    {
        // ── 1. QR Code: generate server-side, sama persis dengan halaman tiket ─
        // Format: 'TKT-' . str_pad($transaction->id, 8, '0', STR_PAD_LEFT)
        $qrData = 'TKT-' . str_pad($this->transaction->id, 8, '0', STR_PAD_LEFT);

        try {
            $result = (new Builder(
                writer: new SvgWriter(),
                data: $qrData,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                size: 150,
                margin: 8,
            ))->build();

            // SVG embed langsung sebagai data URI (support di semua modern email client)
            $this->qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($result->getString());
        } catch (\Throwable $e) {
            $this->qrBase64 = null;
        }

        // ── 2. Poster Event: embed base64 dari storage lokal ─────────────────
        $posterPath = $this->transaction->event->poster_path ?? null;
        if ($posterPath && Storage::disk('public')->exists($posterPath)) {
            $raw  = Storage::disk('public')->get($posterPath);
            $ext  = strtolower(pathinfo($posterPath, PATHINFO_EXTENSION));
            $mime = match ($ext) {
                'jpg', 'jpeg' => 'image/jpeg',
                'gif'         => 'image/gif',
                'webp'        => 'image/webp',
                default       => 'image/png',
            };
            $this->posterBase64 = 'data:' . $mime . ';base64,' . base64_encode($raw);
        }

        return $this;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Resmi Anda: ' . $this->transaction->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
        );
    }
}