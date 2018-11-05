#!/bin/bash

echo "Import DB dump..."

mysql -h"${MYSQL_HOST}" -u"${MYSQL_USER}" -p"${MYSQL_PASSWORD}" -D"${MYSQL_DATABASE}" < /db/dump.sql

echo "DB installed!"
