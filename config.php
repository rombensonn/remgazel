<?php

return [
    'site' => [
        'name' => 'РемГазель',
        'url' => 'https://rombensonn.github.io/remgazel',
        'timezone' => 'Europe/Moscow',
    ],
    'business' => [
        'name' => 'РемГазель',
        'phone' => '+7 (903) 296-06-93',
        'phone_href' => '+79032960693',
        'whatsapp' => 'https://wa.me/79032960693?text=%D0%97%D0%B4%D1%80%D0%B0%D0%B2%D1%81%D1%82%D0%B2%D1%83%D0%B9%D1%82%D0%B5.%20%D0%A5%D0%BE%D1%87%D1%83%20%D1%83%D1%82%D0%BE%D1%87%D0%BD%D0%B8%D1%82%D1%8C%20%D1%80%D0%B5%D0%BC%D0%BE%D0%BD%D1%82%20%D0%93%D0%B0%D0%B7%D0%B5%D0%BB%D0%B8',
        'address' => 'Московская область, Мытищи, проезд № 4530',
        'hours' => 'Ежедневно 08:00–22:00',
        'rating' => '4.3',
        'rating_count' => 8,
    ],
    'lead' => [
        'email_to' => 'mail@example.com',
        'email_from' => 'no-reply@remgazel.ru',
        'email_subject' => 'Новая заявка с сайта РемГазель',
        'telegram_bot_token' => getenv('TELEGRAM_BOT_TOKEN') ?: '',
        'telegram_chat_id' => getenv('TELEGRAM_CHAT_ID') ?: '',
        'rate_limit_window' => 3600,
        'rate_limit_max' => 5,
    ],
];
