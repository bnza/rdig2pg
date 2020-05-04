#!/bin/sh

if ! bin/console doctrine:query:sql --connection=postgres --no-interaction 'SELECT COUNT(*) FROM finding;' > /dev/null;
  then
    echo >&2
    echo "Setting up..."
    bin/console doctrine:database:drop --if-exists --force --connection=postgres --no-interaction
    bin/console doctrine:database:create --connection=postgres --no-interaction
    bin/console doctrine:schema:create --em=postgres --no-interaction
  else
    echo "Postgres DB already set up"
    exit 0
fi