<?php

namespace App\Notifications;

use App\Models\Pegawai;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    public function toMail($notifiable)
    {
        $pegawai = Pegawai::where('peg_id', $notifiable->peg_id)->select('peg_nama_tanpa_gelar')->first();
        $url = url(config('app.client_url') . '/password/reset/' . $this->token) . '?email=' . urlencode($notifiable->email);
        return (new MailMessage)
            ->greeting('Halo, ' . $pegawai->peg_nama_tanpa_gelar)
            ->line('Kami telah menerima permintaan Anda untuk me-reset password. Silahkan klik tombol di bawah ini untuk me-reset password Anda.')
            ->action('Reset Password', $url)
            ->line('Reset password berlaku untuk sistem kepegawaian dan sistem presensi. Abaikan email ini jika Anda tidak pernah meminta untuk me-reset password.');
    }
}
