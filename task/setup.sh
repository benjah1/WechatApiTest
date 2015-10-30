#!/bin/bash

echo "Check docker"
if [ -z "`which docker`" ]; then
	curl -sSL https://get.docker.com/ | sh
	usermod -aG docker vagrant 
fi

echo "check docker-compose"
if [ -z "`which docker-compose`" ]; then
	apt-get install python-pip -y
	pip install docker-compose
fi

DOCKER="dao"

if [ -z "`which dao`" ]; then
  $DOCKER="docker"
fi

$DOCKER pull daocloud.io/library/php:5.6-fpm
$DOCKER pull daocloud.io/library/mysql:5.6


