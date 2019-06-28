<?php

/*
 * Get datetime from history
 */
function ago($string) {
    return date('Y-m-d H:i:s', strtotime($string . ' ago'));
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

function getContractType($squad)
{
    $type = [];
    if (strtolower($squad) == 'u19') {
        $type[] = 'youth';
    } elseif (strtolower($squad) == 'u21') {
        $type[] = 'reserve';
    } else {
        $type[] = 'key';
        $type[] = 'regular';
    }
    return $type;
}

function getPositionsExceptGoalkeeper()
{
    return [
        'LD', 'CLD', 'CD', 'CRD', 'RD',
        'LM', 'CLM', 'CM', 'CRM', 'RM',
        'LF', 'CLF', 'CF', 'CRF', 'RF',
    ];
}