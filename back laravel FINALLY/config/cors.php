<?php

return [
    'paths' => ['api/*'], // Разрешить доступ к API
    'allowed_methods' => ['*'], // Все методы (GET, POST, PUT, DELETE)
    'allowed_origins' => ['*'], // Разрешить запросы со всех доменов (лучше указать конкретный)
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Разрешить все заголовки
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Установи true, если используешь куки
];
