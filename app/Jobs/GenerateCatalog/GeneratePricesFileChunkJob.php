<?php

namespace App\Jobs\GenerateCatalog;

class GeneratePricesFileChunkJob extends AbstractJob
{
    private array $chunk;
    private int $fileNum;

    public function __construct(array $chunk, int $fileNum)
    {
        parent::__construct(); // Викликаємо конструктор батьківського класу для встановлення черги
        $this->chunk = $chunk;
        $this->fileNum = $fileNum;
    }

    public function handle(): void
    {
        $this->debug("Processing chunk {$this->fileNum} with products: " . implode(', ', $this->chunk));
    }
}
