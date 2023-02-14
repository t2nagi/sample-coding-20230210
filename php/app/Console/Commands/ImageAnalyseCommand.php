<?php

namespace App\Console\Commands;

use App\Infrastructure\UseCases\IImageAnalyseUserCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImageAnalyseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:image:analyse {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '画像ファイルを分析し、結果をai_analysis_logに格納する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private IImageAnalyseUserCase $iImageAnalyseUserCase
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('[Start] Image analyse.');

        $result = Command::SUCCESS;
        $path = $this->argument('path');

        try {

            if (!file_exists($path)) {
                throw new \Exception("File is not found. path = [$path]");
            }

            $minetype = mime_content_type($path);
            if (!str_starts_with($minetype, 'image/')) {
                throw new \Exception("File is not image format. path = [$path]");
            }

            $entity = $this->iImageAnalyseUserCase->analyse($path);

            Log::info("S3 path. path = [{$entity->imagePath}]");
            $success = $entity->isSuccess ? 'true' : 'false';
            Log::info("api response. success = [{$success}]");
            Log::info("api response. message = [{$entity->message}]");
            if ($entity->isSuccess) {
                Log::info("api response. class = [{$entity->class}]");
                Log::info("api response. confidence = [{$entity->confidence}]");
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $result = Command::FAILURE;
        }

        Log::info('[End] Image analyse.');
        return $result;
    }
}
