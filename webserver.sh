#!/bin/bash
# Set script directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
php_server_ip=localhost
php_server_port=8000

# Define log and PID file paths within the e2e directory
pid_file="$SCRIPT_DIR/php_server_${php_server_port}.pid"
log_file="$SCRIPT_DIR/php_server_${php_server_port}.log"
error_log_file="$SCRIPT_DIR/php_server_${php_server_port}_error.log"
# Check if port is already in use and restart server if needed
if lsof -n -i :"$php_server_port" > /dev/null; then
    echo "Port $php_server_port is already in use. Attempting to restart the server."
    if [ -f "$pid_file" ]; then
        bash stop-dev-php-server.sh
    else
        echo "No PID file found. Trying to kill the process using the port directly."
        pid=$(lsof -n -t -i:"$php_server_port" -sTCP:LISTEN)
        if [ -n "$pid" ]; then
            kill -9 "$pid"
            echo "Killed process $pid that was using port $php_server_port."
        fi
    fi
fi

# Start PHP server and redirect stdout and stderr to log files
php -S "$php_server_ip":"$php_server_port" - > "$log_file" 2> "$error_log_file" &
php_server_pid=$!
echo $php_server_pid > "$pid_file"
echo "PHP server started on port $php_server_port with PID $php_server_pid."
