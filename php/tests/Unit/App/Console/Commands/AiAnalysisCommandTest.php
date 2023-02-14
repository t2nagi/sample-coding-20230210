<?php

namespace Tests\Unit\App\Console\Commands;

use App\Domains\Entities\AiAnalysisLogEntity;
use App\Infrastructure\UseCases\IImageAnalyseUserCase;
use Illuminate\Console\Command;
use PHPUnit\Framework\ExceptionWrapper;
use Symfony\Component\Console\Exception\RuntimeException;
use Tests\TestCase;

class AiAnalysisCommandTest extends TestCase
{

    public function test001_Success()
    {
        app()->bind(
            IImageAnalyseUserCase::class,
            function () {
                return new class implements IImageAnalyseUserCase
                {
                    public function analyse(string $path): AiAnalysisLogEntity
                    {
                        return new AiAnalysisLogEntity(
                            imagePath: '',
                            isSuccess: true,
                            message: "success",
                            class: 3,
                            confidence: 0.8683,
                            requestTimestamp: 1234567891,
                            responseTimestamp: 1987654321,
                            id: 1
                        );
                    }
                };
            }
        );

        $path = '/volume/success_img.png';

        $result = $this->artisan("command:image:analyse $path");

        $result->assertSuccessful();
    }

    public function test002_ArgumentNotFound()
    {
        $this->expectException(RuntimeException::class);
        $this->artisan("command:image:analyse");
    }

    public function test003_NotImage()
    {
        $path = './works/dummy.png';
        touch($path);

        $result = $this->artisan("command:image:analyse $path");

        $result->assertFailed();
    }
}
