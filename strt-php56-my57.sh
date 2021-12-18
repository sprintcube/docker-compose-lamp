#!/bin/bash

if [ "$1" = "stop" ]
then
	cp -f php56my57.env .env
	docker-compose down
else
	cp -f php56my57.env .env
	docker-compose up -d
fi
