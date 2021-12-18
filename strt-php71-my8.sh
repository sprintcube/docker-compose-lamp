#!/bin/bash

if [ "$1" = "stop" ]
then
	cp -f php71my8.env .env
	docker-compose down
else
	cp -f php71my8.env .env
	docker-compose up -d
fi
