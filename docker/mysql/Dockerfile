FROM mysql:8

COPY /docker/mysql/structure.sql /docker-entrypoint-initdb.d/structure.sql
COPY /docker/mysql/custom.cnf /etc/mysql/conf.d/custom.cnf