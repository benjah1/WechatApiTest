#!/bin/bash

echo "Update source"
sed -i 's/\/\/.*archive.ubuntu.com/mirrors.aliyun.com/g' /etc/apt/sources.list
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
docker version

echo "Pull docker image"
docker pull daocloud.io/library/php:5.6-fpm
docker pull daocloud.io/library/mysql:5.6
docker pull composer:composer
docker tag -f daocloud.io/library/php:5.6-fpm php:5.6-fpm
docker tag -f daocloud.io/library/mysql:5.6 mysql:5.6

docker build -t my/composer -f Dockerfile-composer .
