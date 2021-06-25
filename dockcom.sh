#!/bin/bash

COMPOSE="docker compose"

if [ "$1" == "start" ]; then
    $COMPOSE up -d

elif [ "$1" == "stop" ]; then
    $COMPOSE down

elif [ "$1" == "bash" ]; then
    if [ -n "$2" ]; then
        $COMPOSE exec "$2" bash
    else
        $COMPOSE exec web bash
    fi

elif [ "$1" == "artisan" ]; then
    shift 1
    $COMPOSE exec web php artisan "$@"

elif [ "$1" == "composer" ]; then
    shift 1
    $COMPOSE exec web composer "$@"

else
    $COMPOSE "$@"
fi
