#!/bin/bash

echo "Update source"
# sed -i 's/archive.ubuntu.com/mirrors.aliyun.com/g' /etc/apt/sources.list
apt-get update

echo "Check docker"
if [ -z "`which docker`" ]; then
	curl -sSL https://get.docker.com/ | sh
	usermod -aG docker ubuntu 
fi

echo "Check docker-compose"
if [ -z "`which docker-compose`" ]; then
	apt-get install python-pip -y
	pip install docker-compose
fi

echo "Check docker client"
DOCKER="dao"

if [ -z "`which dao`" ]; then
  $DOCKER="docker"
fi

$DOCKER version

echo "Pull docker image"
$DOCKER pull daocloud.io/library/php:5.6-fpm
$DOCKER pull daocloud.io/library/mysql:5.6
$DOCKER tag daocloud.io/library/php:5.6-fpm php:5.6-fpm
$DOCKER tag daocloud.io/library/mysql:5.6 mysql:5.6
