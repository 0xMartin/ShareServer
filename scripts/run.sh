#!/usr/bin/env bash

# must run as root
if [[ $(id -u) -ne 0 ]];
then
        echo "Please run as root"
        exit 1
fi

service apache2 start
service mysql start
