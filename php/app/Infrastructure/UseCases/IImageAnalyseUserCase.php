<?php

namespace App\Infrastructure\UseCases;

use App\Domains\Entities\AiAnalysisLogEntity;

interface IImageAnalyseUserCase
{
    public function analyse(string $path): AiAnalysisLogEntity;
}
