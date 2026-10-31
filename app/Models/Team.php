<?php

namespace App\Models;

use App\Models\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory, HasPublishStatus;

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
        if (empty($this->social_links)) {
            return;
        }

        $links = [];

        foreach ($this->social_links as $name => $link) {
            if (! is_string($link) || ! preg_match('#^https?://#i', $link)) {
                continue;
            }

            $links[] = sprintf('<a href="%s" target="_blank" rel="noopener">%s</a>', e($link), e($name));
        }

        return implode(', ', $links);
    }
}
