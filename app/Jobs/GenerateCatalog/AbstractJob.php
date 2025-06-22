<?php

namespace App\Jobs\GenerateCatalog;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // Додаємо використання фасаду Log

abstract class AbstractJob implements ShouldQueue // Змінюємо на abstract
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('generate-catalog'); // Усі Jobs, успадковані від цього, будуть за замовчуванням в черзі 'generate-catalog'
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->debug('done'); // Метод за замовчуванням для кожного Job
    }

    /**
     * Хелпер для запису в лог з інформацією про клас Job.
     * @param string $msg
     * @return void
     */
    protected function debug(string $msg): void
    {
        $class = static::class; // Отримуємо повне ім'я класу поточного Job
        $msg = $msg . " [{$class}]";
        Log::info($msg); // Використовуємо фасад Log для запису
    }
}
