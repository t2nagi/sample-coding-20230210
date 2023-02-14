<?php

namespace App\Infrastructure\Repositories;

use App\Domains\Entities\AiAnalysisLogEntity;

interface IAiAnalysisLogRepository
{
    public function save(AiAnalysisLogEntity $entity): AiAnalysisLogEntity;

    // other function 
    // public static function find(int $id): AiAnalysisLogEntity
    // public function update(AiAnalysisLogEntity $aiAnalysisLogEntity): int
    // public static function delete(int $id): int
}
