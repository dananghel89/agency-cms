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
            'admin' => config('sb.seed.admin_email'),
            'editor' => config('sb.seed.editor_email'),
        ]);
    }

    public static function socialNetworks(): Collection
    {
        return collect([
            [
                'name' => 'facebook',
                'label' => 'Facebook',
                'url' => 'https://www.facebook.com/',
                'icon' => 'fa-facebook-f',
            ],
            [
                'name' => 'twitter',
                'label' => 'Twitter',
                'url' => 'https://www.twitter.com/',
                'icon' => 'fa-twitter',
            ],
            [
                'name' => 'linkedin',
                'label' => 'LinkedIn',
                'url' => 'https://www.linkedin.com/',
                'icon' => 'fa-linkedin-in',
            ],
        ]);
    }
}
