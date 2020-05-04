FROM mysql:5.7

ARG DUMP_IN
ARG DBNAME
ARG RDIG_USER

COPY "./dumps/${DUMP_IN}" /docker-entrypoint-initdb.d/

RUN sed -i "1i CREATE DATABASE $DBNAME CHARACTER SET UTF8 COLLATE utf8_bin;  GRANT ALL PRIVILEGES ON $DBNAME.* TO '$RDIG_USER'@'%'; USE $DBNAME;" /docker-entrypoint-initdb.d/${DUMP_IN}