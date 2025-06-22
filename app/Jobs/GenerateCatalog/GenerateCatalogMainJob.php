<?php

namespace App\Jobs\GenerateCatalog;

use Throwable; // Додаємо use для Throwable, якщо потрібно
use Illuminate\Support\Collection; // Додаємо use для Collection, якщо використовується

class GenerateCatalogMainJob extends AbstractJob
{
    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        $this->debug('start');

        // Спочатку кешуємо продукти (виконуємо синхронно, без черги)
        // dispatchNow() виконує Job негайно, не поміщаючи його в чергу
        GenerateCatalogCacheJob::dispatchSync(); // Використовуємо dispatchSync() для синхронного виконання

        // Створюємо ланцюг завдань формування файлів з цінами
        $chainPrices = $this->getChainPrices();

        // Основні підзавдання
        $chainMain = [
            new GenerateCategoriesJob(),   // Генерація категорій
            new GenerateDeliveriesJob(),   // Генерація способів доставок
            new GeneratePointsJob(),       // Генерація пунктів видачі
        ];

        // Підзавдання, які мають виконуватися останніми
        $chainLast = [
            new ArchiveUploadsJob(),       // Архівування файлів і перенесення архіву в публічний каталог
            new SendPriceRequestJob(),     // Відправка повідомлення зовнішньому сервісу
        ];

        // Об'єднуємо всі ланцюги
        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        // Запускаємо перший Job в ланцюзі, який потягне за собою всі інші
        // GenerateGoodsFileJob::withChain($chain)->dispatch(); // Цей синтаксис також працює
        GenerateGoodsFileJob::dispatch()->chain($chain); // Більш читабельний синтаксис

        $this->debug('finish');
    }

    /**
     * Формування ланцюгів підзавдань по генерації файлів з цінами
     *
     * @return array<AbstractJob>
     */
    private function getChainPrices(): array
    {
        $result = [];
        // Імітуємо колекцію продуктів. У реальному додатку це будуть реальні продукти з БД.
        $products = collect([1, 2, 3, 4, 5, 6, 7]); // Змінив кількість для демонстрації chunk
        $fileNum = 1;
        $chunkSize = 2; // Розмір "чанку"

        foreach ($products->chunk($chunkSize) as $chunk) {
            // Кожен шматок продуктів обробляється окремим Job
            $result[] = new GeneratePricesFileChunkJob($chunk->toArray(), $fileNum); // Передаємо chunk як масив
            $fileNum++;
        }

        return $result;
    }
}
