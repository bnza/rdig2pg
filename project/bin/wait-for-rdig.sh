#!/bin/sh
# wait-for-rdig.sh

set -e

until bin/console doctrine:query:sql 'SELECT COUNT(*) FROM finding;' > /dev/null ; do
  >&2 echo "rDig is not ready - sleeping"
  sleep 5
done

>&2 echo "rDig is up - proceeding"
exit 0

