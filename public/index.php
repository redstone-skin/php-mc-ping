<?php
require_once __DIR__ . '/../vendor/autoload.php';
$klein = new \Klein\Klein();
$klein->respond('GET', '/api/status/[:address]', function ($request,$response) {
    // check address vaild
    $address = $request->address;
    $isValid = preg_match('/(([a-z0-9]|[a-z0-9][a-z0-9\-]*[a-z0-9])\.)*([a-z0-9]|[a-z0-9][a-z0-9\-]*[a-z0-9])(:[0-9]+)?$/', $address);
    if (!$isValid) {
        return $response->json([
            'online' => false,
        ]);
    }

    $ping_result = ping_minecraft($address);
    if(!$ping_result) {
        return $response->json([
            'online' => false,
        ]);
    }

    // If motd is json, convert to color code
    if (is_array($ping_result['description'])) {
        $ping_result['description'] = json_to_color_code($ping_result['description']);
    }

    return $response->json([
        'online' => true,
        'icon' => $ping_result['favicon'],
        'motd' => [
            'raw' => $ping_result['description'],
            'clean' => color_code_clean($ping_result['description']),
            'html' => color_code_to_html($ping_result['description']),
        ],
        'players' => [
            'online' => $ping_result['players']['online'],
            'max' => $ping_result['players']['max'],
        ],
    ]);
});

$klein->dispatch();