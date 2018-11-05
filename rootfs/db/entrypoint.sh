#!/bin/bash

/db/wait.sh

if ! mysql -h"${MYSQL_HOST}" -u"${MYSQL_USER}" -p"${MYSQL_PASSWORD}" -e"SELECT 1 FROM ${MYSQL_DATABASE}.wp_users LIMIT 1;" &> /dev/null; then
    /db/setup.sh
fi

apache2-foreground
