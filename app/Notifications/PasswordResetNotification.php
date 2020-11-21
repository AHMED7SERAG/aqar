<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class PasswordResetNotification extends Notification
{
    use Queueable;
    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token=$token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $urlToResetForm = "https://salonic.local/api/admin/password/reset/?token=". $this->token;
        return (new MailMessage)
            ->greeting('مرحبا !')
            ->subject(Lang::get('إشعار إعاة تعين كلمة المرور'))
            ->line(Lang::get('لقد طلبت إعادة تعين كلمة المرور'))
            ->action(Lang::get('إعادة تعيين كلمة المرور'), $urlToResetForm)
            ->line(Lang::get('ستنتهي صلاحية رابط إعادة تعيين كلمة المرور خلال:count دقيقة.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::get('إذا لم تطلب إعادة تعيين كلمة المرور ، فلا يلزم اتخاذ أي إجراء آخر.    Token  :   ==>   '. $this->token));

    }

    

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
