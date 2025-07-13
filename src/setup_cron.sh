#!/bin/bash
php_path=$(which php)
cron_path=$(crontab -l 2>/dev/null | grep -v "cron.php"; echo "*/5 * * * * $php_path $(pwd)/cron.php")
echo "$cron_path" | crontab -
echo "Cron job set to run every 5 minutes."
