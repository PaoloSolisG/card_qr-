<?php

/*
|--------------------------------------------------------------------------
| Digital business card defaults
|--------------------------------------------------------------------------
|
| Company-wide values used on every card when an agent does not define
| their own. The card URL encoded in each QR is built from APP_URL, so
| point APP_URL at the public domain before printing QR codes.
|
*/

return [

    'company' => env('CARD_COMPANY', 'Pacific Shipping Group'),

    'website' => env('CARD_WEBSITE'),

    'instagram' => env('CARD_INSTAGRAM'),

    'facebook' => env('CARD_FACEBOOK'),

    /*
    | Country calling code added to local numbers left by visitors
    | (e.g. "987654321") when opening them in WhatsApp from the panel.
    */
    'country_code' => env('CARD_COUNTRY_CODE', '51'),

];
