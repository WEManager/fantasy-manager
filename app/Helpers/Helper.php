<?php

function slugify($string) {
    return \Illuminate\Support\Str::slug($string);
}

function nationalityBasedOnLocale($locale) {
    if ($locale == 'sv') {
        return 'SE';
    }

    return strtoupper($locale);
}

function getContractType($squad) {
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

function getPositionsExceptGoalkeeper() {
    return [
        'LD', 'CLD', 'CD', 'CRD', 'RD',
        'LM', 'CLM', 'CM', 'CRM', 'RM',
        'LF', 'CLF', 'CF', 'CRF', 'RF',
    ];
}