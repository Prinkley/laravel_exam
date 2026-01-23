<?php

namespace App\Jobs;

use App\Mail\ThingDescriptionChanged;
use App\Models\Thing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendThingDescriptionChangedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Thing $thing,
        public ?string $oldDescription = null
    )
    {
        // Задание 18: Устанавливаем очередь для фонового выполнения
        $this->queue = 'default';
        $this->delay(0); // Можно установить задержку, если нужно
    }

    public function handle(): void
    {
        // Отправляем письмо владельцу вещи
        Mail::to($this->thing->master->email)
            ->send(new ThingDescriptionChanged($this->thing, $this->oldDescription));
    }

    public function failed(\Throwable $exception): void
    {
        // Обработка ошибки при отправке письма
        \Log::error('Failed to send ThingDescriptionChanged email', [
            'thing_id' => $this->thing->id,
            'error' => $exception->getMessage()
        ]);
    }
}
