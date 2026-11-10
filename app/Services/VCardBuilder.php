<?php

namespace App\Services;

use App\Models\Agent;

class VCardBuilder
{
    /**
     * Build a vCard 3.0 so phones can save the agent as a contact.
     */
    public function build(Agent $agent): string
    {
        $nameParts = preg_split('/\s+/', trim($agent->name)) ?: [];
        $firstName = array_shift($nameParts) ?? '';
        $lastName = implode(' ', $nameParts);

        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'N:'.$this->escape($lastName).';'.$this->escape($firstName).';;;',
            'FN:'.$this->escape($agent->name),
            'ORG:'.$this->escape(config('card.company')),
        ];

        if ($agent->position) {
            $lines[] = 'TITLE:'.$this->escape($agent->position);
        }

        if ($agent->phone) {
            $lines[] = 'TEL;TYPE=WORK,VOICE:'.$this->escape($agent->phone);
        }

        if ($agent->whatsapp && $agent->whatsapp !== $agent->phone) {
            $lines[] = 'TEL;TYPE=CELL:+'.preg_replace('/\D+/', '', $agent->whatsapp);
        }

        if ($agent->email) {
            $lines[] = 'EMAIL;TYPE=WORK,INTERNET:'.$this->escape($agent->email);
        }

        if ($website = $agent->websiteUrl()) {
            $lines[] = 'URL:'.$this->escape($website);
        }

        $lines[] = 'END:VCARD';

        return implode("\r\n", $lines)."\r\n";
    }

    private function escape(string $value): string
    {
        return addcslashes($value, ',;\\');
    }
}
