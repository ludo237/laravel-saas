#!/bin/sh

echo "[queue-worker] Starting Laravel queue worker..."

while true; do
  php artisan queue:work \
    --queue=default,notifications,emails \
    --sleep=10 \
    --tries=3 \
    --json \
    --timeout=60 \
    --verbose

  echo "[queue-worker] Worker exited. Restarting in 5 seconds..."
  sleep 5
done
