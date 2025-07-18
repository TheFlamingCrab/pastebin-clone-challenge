#!/bin/bash
php -S 0.0.0.0:8000 -t /app &
node bot.js &

wait

