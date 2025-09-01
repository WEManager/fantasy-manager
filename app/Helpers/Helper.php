<?php

declare(strict_types=1);

use App\Models\Season;

function route_path($name, array $parameters = [], $extension = '')
{
    $url = link_route($name, $parameters) . $extension;
    return str_replace(config('app.url') . '/', '', $url);
}

function is_current($name, array $parameters = [], $extension = '')
{
    $parameters['locale'] = app()->getLocale();

    return request()->is(route_path($name, $parameters, $extension));
}

function getCurrentSeason()
{
    $date = date('Y-m-d H:i:s');
    return Season::whereDate('start_time', '<=', $date)->whereDate('end_time', '>=', $date)->first();
}

function getContractType($squad)
{
  return match (strtolower($squad)) {
    'u19' => ['youth'],
    'u21' => ['reserve'],
    default => ['key', 'regular'],
  };
}

function getPositions()
{
    return [
        'GK',
        'LD', 'CLD', 'CD', 'CRD', 'RD',
        'LM', 'CLM', 'CM', 'CRM', 'RM',
        'LF', 'CLF', 'CF', 'CRF', 'RF',
    ];
}

function getPositionsExceptGoalkeeper()
{
    $positions = getPositions();

    foreach ($positions as $key => $position) {
        if ($position === 'GK') unset($positions[$key]);
    }

    return $positions;
}

function translations(String $jsonFile) {
    if(!file_exists($jsonFile)) {
        return [];
    }

    return json_decode(file_get_contents($jsonFile), true);
}
