<?php

namespace App\Models;

use App\Models\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, HasPublishStatus;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'content',
        'template',
        'status',
    ];
}
