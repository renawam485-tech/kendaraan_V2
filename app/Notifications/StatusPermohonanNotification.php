<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class StatusPermohonanNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $permohonan;
    public $pesan;

    public function __construct($permohonan, $pesan)
    {
        $this->permohonan = $permohonan;
        $this->pesan = $pesan;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'permohonan_id' => $this->permohonan->id,
            'pesan' => $this->pesan,
            'status' => $this->permohonan->status_permohonan,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'permohonan_id' => $this->permohonan->id,
            'pesan' => $this->pesan,
            'status' => $this->permohonan->status_permohonan,
        ]);
    }
}