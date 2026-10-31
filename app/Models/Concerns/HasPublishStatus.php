<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasPublishStatus
{
    /**
     * Scope a query to only include published items.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'publish');
    }
}
