#!/usr/bin/env bash

DATE_TIME=$(date +%y%m%dT%H%M%S)

export PGPASSWORD="${POSTGRES_PASSWORD}"; \
pg_dump --file "/mnt/shared/${DATE_TIME}.sql"  \
  --host "localhost" --port "5432" --username "${POSTGRES_USER}"  \
  --verbose --format=p "${POSTGRES_DB}"