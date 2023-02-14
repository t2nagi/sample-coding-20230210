<?php

namespace App\Domains\UseCases;

use App\Domains\Entities\AiAnalysisLogEntity;
use App\Infrastructure\Repositories\IAiAnalysisLogRepository;
use App\Infrastructure\UseCases\IImageAnalyseUserCase;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ImageAnalyseUserCase implements IImageAnalyseUserCase
{

    public function __construct(
        private IAiAnalysisLogRepository $aiAnalysisLogRepository
    ) {
        // Empty
    }

    public function analyse(string $path): AiAnalysisLogEntity
    {
        // S３にアップロード
        $name = basename($path);
        $now = Carbon::now();
        $s3Path = $now->format('Y/m/d/H/i/s/v') . '/' . $name;
        Storage::disk('s3')->put(
            $s3Path,
            file_get_contents($path)
        );

        $requestTimestamp = time();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
            ->post(config('const.outside_services.ai_analyse.url'), [
                'image_path' => $s3Path
            ]);
        $responseTimestamp = time();

        if ($response->status() == Response::HTTP_OK) {
            $entity = new AiAnalysisLogEntity(
                imagePath: $s3Path,
                isSuccess: $response->json('success'),
                message: $response->json('message'),
                class: $response->json('estimated_data')['class'] ?? null,
                confidence: $response->json('estimated_data')['confidence'] ?? null,
                requestTimestamp: $requestTimestamp,
                responseTimestamp: $responseTimestamp
            );
        } else {
            throw new Exception("Http status is not 200. status = {$response->status()}");
        }

        $entity = $this->aiAnalysisLogRepository->save($entity);

        return $entity;
    }
}
