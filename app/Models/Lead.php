<?php

namespace App\Models;

use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'phone'])]
class Lead extends Model
{
    /** @use HasFactory<LeadFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Agent, $this>
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * WhatsApp chat link; local numbers get the configured country code.
     */
    public function whatsappUrl(): string
    {
        $digits = preg_replace('/\D+/', '', $this->phone);

        if (! str_starts_with(trim($this->phone), '+') && strlen($digits) <= 10) {
            $digits = config('card.country_code').ltrim($digits, '0');
        }

        return 'https://wa.me/'.$digits;
    }
}
