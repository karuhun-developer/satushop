<?php

return [
    'client_id' => env('DOKU_CLIENT_ID', ''),
    'secret_key' => env('DOKU_SECRET_KEY', ''),
    'production' => (bool) env('DOKU_PRODUCTION', false),
];
