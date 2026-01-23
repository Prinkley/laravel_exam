<?php

namespace App\Notifications;

use App\Models\Thing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThingDescriptionUpdated extends Notification
{
    use Queueable;

    public function __construct(public Thing $thing, public ?string $oldDescription = null)
    {
    }

    public function via(object $notifiable): array
    {
        // Задание 19: Уведомление отправляется через БД и по email
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject("Вещь '{$this->thing->name}' была обновлена")
            ->greeting("Привет, {$notifiable->name}!")
            ->line("Описание вещи '{$this->thing->name}' было изменено.")
            ->when($this->oldDescription, function ($message) {
                $message->line("Старое описание: " . $this->oldDescription);
            })
            ->line("Новое описание: " . $this->thing->description)
            ->action('Посмотреть вещь', route('things.show', $this->thing))
            ->line('Спасибо за использование нашей системы!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'thing_id' => $this->thing->id,
            'thing_name' => $this->thing->name,
            'message' => "Описание вещи '{$this->thing->name}' было обновлено",
            'old_description' => $this->oldDescription,
            'new_description' => $this->thing->description,
            'url' => route('things.show', $this->thing)
        ];
    }
}
