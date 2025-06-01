<?php

use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response([
        'status' => true
    ]);
});

Route::get('test/amqp/{type}/{channel}', function (string $type, string $channel) {

    $payload = [
        "type" => $type,
        "channel" => $channel,
        "to" => "test.hub@gmail.com",
        'message' => "Test message for amqp",
        "userId" => 1,
        "data" => [
            "firstName" => "Test",
            "lastName" => "hub",
            "verificationCode" => "2355",
            "verificationUrl" => "http://localhost:800/test/url"
        ]
    ];
    Amqp::publish('test-queue11', json_encode($payload), [
        'content_type' => 'application/json',
        'delivery_mode' => 2, // Make message persistent
        'exchange' => 'laravel-exchange1',
        'queue' => 'notify-queue12',
        'exchange_type' => 'direct',
    ]);
});
