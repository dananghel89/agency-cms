<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Options extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
    ];

    public static function userEmails(): Collection
    {
        return collect([
            'admin' => 'admin@example.com',
            'editor' => 'editor@example.com',
        ]);
    }

    public static function socialNetworks(): Collection
    {
        return collect([
            [
                'name' => 'facebook',
                'url' => 'https://www.facebook.com/',
                'icon' => 'fa-facebook-f',
            ],
            [
                'name' => 'twitter',
                'url' => 'https://www.twitter.com/',
                'icon' => 'fa-twitter',
            ],
            [
                'name' => 'linkedin',
                'url' => 'https://www.linkedin.com/',
                'icon' => 'fa-linkedin-in',
            ],
        ]);
    }
}
