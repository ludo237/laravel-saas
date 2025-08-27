#!/bin/bash

if [[ $# -eq 0 ]]
  then
    echo "You need to supply a filename for the filter"
    exit 1
fi

php artisan test --bail --filter "$1"

