<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Domain extends Model
{
    protected $fillable = [
        'name',
        'tld',
        'registrar',
        'status',
        'registered_at',
        'expires_at',
        'country',
        'last_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'expires_at' => 'datetime',
            'last_checked_at' => 'datetime',
        ];
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(DomainMetric::class);
    }

    public function latestMetric(): HasOne
    {
        return $this->hasOne(DomainMetric::class)->latestOfMany();
    }

    public function watchlistItems(): HasMany
    {
        return $this->hasMany(WatchlistItem::class);
    }

    public function getAgeInYearsAttribute(): ?int
    {
        if (!$this->registered_at) {
            return null;
        }

        return Carbon::parse($this->registered_at)->diffInYears(now());
    }

    public function getDaysToExpiryAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        return Carbon::parse($this->expires_at)->diffInDays(now(), false);
    }
}
