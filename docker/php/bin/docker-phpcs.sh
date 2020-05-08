#!/bin/bash

/usr/bin/docker-compose exec -T php /opt/project/vendor/bin/php-cs-fixer "$@"