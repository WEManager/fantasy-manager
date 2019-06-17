<?php

function nationalityBasedOnLocale($locale) {
    if ($locale == 'sv') {
        return 'SE';
    }

    return strtoupper($locale);
}