#!/bin/sh

echo "[schedule-worker] Starting Laravel schedule worker..."

while true; do
  php artisan schedule:work --verbose --no-interaction

  echo "[schedule-worker] Scheduler exited. Restarting in 30 seconds..."
  sleep 30
done
