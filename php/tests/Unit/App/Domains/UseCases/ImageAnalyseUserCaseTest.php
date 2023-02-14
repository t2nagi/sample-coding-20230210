<?php

namespace Tests\Unit\App\Domains\UseCases;

use App\Domains\Repositories\AiAnalysisLogRepository;
use App\Domains\UseCases\ImageAnalyseUserCase;
use App\Infrastructure\UseCases\IImageAnalyseUserCase;
use App\Models\AiAnalysisLog;
use ErrorException;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageAnalyseUserCaseTest extends TestCase
{
    private IImageAnalyseUserCase $iImageAnalyseUserCase;

    public function __construct()
    {
        parent::__construct();

        $this->iImageAnalyseUserCase = new ImageAnalyseUserCase(
            new AiAnalysisLogRepository()
        );
    }
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }


    public function test001_ApiSuccessResponse()
    {
        AiAnalysisLog::truncate();

        $path = './works/dummy.png';
        touch($path);

        Http::fake([
            config('const.outside_services.ai_analyse.url') => Http::response(
                [
                    'success' => true,
                    'message' => 'success',
                    'estimated_data' => [
                        'class' => 3,
                        'confidence' => 0.8683
                    ]
                ],
                200
            )
        ]);

        $entity = $this->iImageAnalyseUserCase->analyse($path);

        $this->assertDatabaseHas('ai_analysis_logs', [
            'id' => $entity->id,
            'image_path' => $entity->imagePath,
            'is_success' => true,
            'message' => 'success',
            'class' => 3,
            'confidence' => 0.8683,
            'request_timestamp' => $entity->requestTimestamp,
            'response_timestamp' => $entity->responseTimestamp,
        ]);

        $this->assertTrue(
            Storage::disk('s3')
                ->exists($entity->imagePath)
        );
    }

    public function test002_ApiFailureResponse()
    {
        AiAnalysisLog::truncate();

        $path = './works/dummy.png';
        touch($path);

        Http::fake([
            config('const.outside_services.ai_analyse.url') => Http::response(
                [
                    'success' => false,
                    'message' => 'Error:E50012',
                    'estimated_data' => []
                ],
                200
            )
        ]);

        $entity = $this->iImageAnalyseUserCase->analyse($path);

        $this->assertDatabaseHas('ai_analysis_logs', [
            'id' => $entity->id,
            'image_path' => $entity->imagePath,
            'is_success' => false,
            'message' => 'Error:E50012',
            'class' => null,
            'confidence' => null,
            'request_timestamp' => $entity->requestTimestamp,
            'response_timestamp' => $entity->responseTimestamp,
        ]);

        $this->assertTrue(
            Storage::disk('s3')
                ->exists($entity->imagePath)
        );
    }

    public function test003_ApiHttpStatusNg()
    {
        AiAnalysisLog::truncate();

        $path = './works/dummy.png';
        touch($path);

        Http::fake([
            config('const.outside_services.ai_analyse.url') => Http::response(
                [],
                404
            )
        ]);

        $count = AiAnalysisLog::count();

        $this->expectException(Exception::class);

        $this->iImageAnalyseUserCase->analyse($path);
    }

    public function test004_ImageNotFound()
    {
        AiAnalysisLog::truncate();

        $path = './works/NotFound.png';

        $count = AiAnalysisLog::count();

        $this->expectException(ErrorException::class);

        $this->iImageAnalyseUserCase->analyse($path);
    }
}
