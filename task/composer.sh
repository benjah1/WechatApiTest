#!/bin/bash

echo "Composer"

docker run --rm -v $(pwd)/wechatApiTest:/app my/composer install
