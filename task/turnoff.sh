#!/bin/bash

docker-compose stop
OFF=$?
docker-compose rm -f
OFF=$?
