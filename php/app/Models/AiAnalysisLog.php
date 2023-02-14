<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiAnalysisLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'is_success',
        'message',
        'class',
        'confidence',
        'request_timestamp',
        'response_timestamp',
    ];
}
