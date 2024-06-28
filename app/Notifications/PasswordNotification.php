<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordNotification extends Notification implements ShouldQueue
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
                    ->subject('Password Generated Notifications')
                    ->view('emails.password_generated', [
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
    public function failed($exception)
    {
        // Log the error message
        Log::error('Notification failed: ' . $exception->getMessage());

        // Optionally, log additional information
        Log::error('Notification failed for user: ' . $this->notifiable->id);
    }
}
