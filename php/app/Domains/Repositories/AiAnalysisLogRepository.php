<?php

namespace App\Domains\Repositories;

use App\Domains\Entities\AiAnalysisLogEntity;
use App\Infrastructure\Repositories\IAiAnalysisLogRepository;
use App\Models\AiAnalysisLog;

class AiAnalysisLogRepository implements IAiAnalysisLogRepository
{
    public function save(AiAnalysisLogEntity $entity): AiAnalysisLogEntity
    {
        if (empty($entity->id)) {
            $aiAnalysisLog = AiAnalysisLog::create(
                [
                    'image_path' => $entity->imagePath,
                    'is_success' => $entity->isSuccess,
                    'message' => $entity->message,
                    'class' => $entity->class,
                    'confidence' => $entity->confidence,
                    'request_timestamp' => $entity->requestTimestamp,
                    'response_timestamp' => $entity->responseTimestamp
                ]
            );
            $entity->id = $aiAnalysisLog->id;
        } else {
            AiAnalysisLog::where('id', $entity->id)
                ->update(
                    [
                        'image_path' => $entity->imagePath,
                        'is_success' => $entity->isSuccess,
                        'message' => $entity->message,
                        'class' => $entity->class,
                        'confidence' => $entity->confidence,
                        'request_timestamp' => $entity->requestTimestamp,
                        'response_timestamp' => $entity->responseTimestamp
                    ]
                );
        }


        return $entity;
    }
}
