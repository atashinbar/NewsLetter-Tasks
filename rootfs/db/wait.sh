#!/bin/bash

echo "Waiting for MySQL.."

until mysql -h "${MYSQL_HOST}" -u"${MYSQL_USER}" -p"${MYSQL_PASSWORD}" -e "SHOW DATABASES;" &> /dev/null; do
    echo "."
    sleep 1
done

echo "MySQL is up!"
