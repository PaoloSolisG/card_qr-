<?php

namespace App\Models;

use Database\Factories\AgentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'position', 'phone', 'whatsapp', 'email', 'website', 'instagram', 'facebook', 'is_active'])]
class Agent extends Model
{
    /** @use HasFactory<AgentFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Generate a unique slug from the name when none is given.
     */
    protected static function booted(): void
    {
        static::saving(function (Agent $agent): void {
            $agent->slug = static::uniqueSlug($agent->slug ?: $agent->name, $agent->id);
        });
    }

    /**
     * Build a slug that no other agent is using.
     */
    public static function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'agente';
        $slug = $base;
        $suffix = 2;

        while (static::query()->where('slug', $slug)->when($ignoreId, fn (Builder $query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return HasMany<Lead, $this>
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * @param  Builder<Agent>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Public card URL encoded in the QR, always based on APP_URL.
     */
    public function cardUrl(): string
    {
        return rtrim(config('app.url'), '/').route('card.show', $this, absolute: false);
    }

    public function whatsappUrl(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->whatsapp);

        return $digits ? 'https://wa.me/'.$digits : null;
    }

    public function phoneUrl(): ?string
    {
        $number = preg_replace('/[^\d+]+/', '', (string) $this->phone);

        return $number ? 'tel:'.$number : null;
    }

    public function emailUrl(): ?string
    {
        return $this->email ? 'mailto:'.$this->email : null;
    }

    public function websiteUrl(): ?string
    {
        return $this->normalizeUrl($this->website ?: config('card.website'));
    }

    public function instagramUrl(): ?string
    {
        return $this->normalizeUrl($this->instagram ?: config('card.instagram'), 'https://instagram.com/');
    }

    public function facebookUrl(): ?string
    {
        return $this->normalizeUrl($this->facebook ?: config('card.facebook'), 'https://facebook.com/');
    }

    /**
     * Turn a bare domain or @handle into a full URL.
     */
    private function normalizeUrl(?string $value, ?string $profileBase = null): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        if ($profileBase && ! Str::contains($value, '.')) {
            return $profileBase.ltrim($value, '@');
        }

        return 'https://'.ltrim($value, '/');
    }
}
