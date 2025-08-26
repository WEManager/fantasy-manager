<?php

/*
 * Get datetime from history
 */

use App\Models\Season;

function ago($string, $precision = null)
{
    if ($precision == null) $format = 'Y-m-d H:i:s';
    if ($precision == 'date') $format = 'Y-m-d';

    return date($format, strtotime($string . ' ago'));
}

function link_route($name, $parameters = [], $absolute = true)
{
    if (!is_array($parameters)) {
        throw new Exception('Parameter $parameters has to be an array');
    }
    $parameters = setCurrentLocale($parameters);

    return app('url')->route($name, $parameters, $absolute);
}

/**
 * @param $parameters
 * @return array
 */
function setCurrentLocale($parameters): array
{
    if (!isset($parameters['locale'])) {
        $locale = ['locale' => app()->getLocale()];
        $parameters = array_merge($parameters, $locale);
    }
    return $parameters;
}

function slugify($string)
{
    return \Illuminate\Support\Str::slug($string);
}

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

function nationalityBasedOnLocale($locale)
{
    if ($locale == 'sv') {
        return 'SE';
    }

    return strtoupper($locale);
}

function localeBasedOnNationality($nationality)
{
    if ($nationality == 'SE') {
        return 'sv';
    }

    return strtolower($nationality);
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
