<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $clientDetail;
    /**
     * Create a new notification instance.
     */
    public function __construct($clientDetail)
    {
      $this->clientDetail = $clientDetail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Activity Notifications')
                    ->view('emails.app_notification', [
                        'user' => $notifiable,
                        'data' => $this->clientDetail
                    ]);
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
           "client_id"=>$this->clientDetail['id'],
           "full_name"=>$this->clientDetail['full_name'],
           "email"=>$this->clientDetail['email'],
           "phone_number"=>$this->clientDetail['phone_number']
        ];
    }
}
