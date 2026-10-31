<?php

namespace App\Models;

use App\Models\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory, HasPublishStatus;

    protected $table = 'portfolio';

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'client',
        'category',
        'position',
        'status',
    ];
}
