#!/bin/bash
chmod 777 app/data
chmod 777 app/data/dl
chmod 777 app/data/uploaded
chmod 777 app/message/file
chmod 777 app/member_picture
chmod 777 app/searchpath.txt
docker compose build
docker compose up -d