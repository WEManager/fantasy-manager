#!/bin/bash

echo Generating new key
php artisan key:generate

echo Refreshing the database
php artisan migrate:refresh

echo Creating 16 swedish clubs
php artisan club:generate 16

echo Creating 16 spanish clubs
php artisan club:generate 16 es

echo Creating 16 german clubs
php artisan club:generate 16 de

echo Creating 16 british clubs
php artisan club:generate 16 gb

echo Creating 16 italian clubs
php artisan club:generate 16 it
