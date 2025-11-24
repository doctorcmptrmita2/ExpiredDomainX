<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DomainMetric extends Model
{
    protected $fillable = [
        'domain_id',
        'organic_traffic',
        'organic_keywords',
        'backlinks_total',
        'referring_domains',
        'ed_score',
        'raw_provider_payload',
    ];

    protected function casts(): array
    {
        return [
            'raw_provider_payload' => 'array',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}
