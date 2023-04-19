#!/usr/bin/env bash

if [ $# -gt 0 ]; then
    exec gosu developer "$@"
else
    service nginx start
    service php8.2-fpm start
    LOGFILE=/var/www/storage/logs/laravel.log
    if [ ! -f "$LOGFILE" ]; then
        gosu developer touch "$LOGFILE"
    fi
    tail -f "$LOGFILE"
fi
