<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'team';

    protected $fillable = [
        'name',
        'profession',
        'social_links',
        'position',
        'status',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    protected $appends = [
        'social_links_html',
    ];

    public function getSocialLinksHtmlAttribute()
    {
        if (empty($this->social_links)) return;

        $separator = "&comma;\r\n";

        $out = '';

        foreach ($this->social_links as $name => $link) {
            $out .= sprintf('<a href="%s" target="_blank">%s</a>', $link, $name) . $separator;
        }

        return rtrim($out, $separator);
    }
}
