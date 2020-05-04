#!/bin/sh

set -e

EM=${1:-mysql}

until bin/console doctrine:query:sql 'SELECT COUNT(*) FROM finding;' --connection="$EM" > /dev/null; do
  >&2 echo "rDig ($EM) is not ready - sleeping"
  sleep 5
done

>&2 echo "rDig ($EM) is up - proceeding"
exit 0