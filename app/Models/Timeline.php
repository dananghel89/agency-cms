<?php

namespace App\Models;

use App\Models\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    use HasFactory, HasPublishStatus;

    protected $table = 'timeline';

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'position',
        'status',
    ];
}
