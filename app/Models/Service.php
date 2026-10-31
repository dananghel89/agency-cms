<?php

namespace App\Models;

use App\Models\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, HasPublishStatus;

    protected $fillable = [
        'title',
        'content',
        'icon',
        'position',
        'status',
    ];
}
