<?php

namespace App\Domains\Entities;


class AiAnalysisLogEntity
{
    public function __construct(
        public string $imagePath,
        public bool $isSuccess,
        public string $message,
        public ?string $class,
        public ?float $confidence,
        public int $requestTimestamp,
        public int $responseTimestamp,
        public ?int $id = null
    ) {
        // Empty
    }
}
