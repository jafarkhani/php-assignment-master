#!/bin/bash

container_id=`docker-compose ps -q api | head -n1`

if [[ -z $container_id ]]; then
	echo "Container is not running."
	echo

	echo "First initialize container with:"
	echo " $ docker-compose up -d"
	exit 1
fi

app_url=`docker port "$container_id" 8080/tcp | sed s/0\.0\.0\.0/127.0.0.1/`

if [[ -z $app_url ]]; then
	echo "could not find app, check docker with: docker-compose ps"
	exit 1
fi

yarn test -e "http://$app_url/postman-env.php"

