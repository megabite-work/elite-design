#!/bin/bash
# service supervisor start
# supervisorctl reread
# supervisorctl update
# supervisorctl start laravel-worker:*
docker-php-entrypoint php-fpm
