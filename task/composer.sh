#!/bin/bash

echo "Composer"

docker run -v $(pwd):/app composer/composer install
