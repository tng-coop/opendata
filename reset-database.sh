#!/bin/bash

# Parse values from app.json
DB_HOST=$(jq -r '.database.host' app.json)
DB_NAME=$(jq -r '.database.dbname' app.json)
DB_USER=$(jq -r '.database.user' app.json)  # Parse the username
DB_PASSWORD=$(jq -r '.database.pass' app.json)
DB_PORT=$(jq -r '.database.port' app.json)

# Export password and port for non-interactive psql commands
export PGPASSWORD=$DB_PASSWORD
export PGPORT=$DB_PORT

# Terminate active connections to the database
psql -h $DB_HOST -U $DB_USER -d postgres -c "SELECT pg_terminate_backend(pg_stat_activity.pid)
  FROM pg_stat_activity
  WHERE pg_stat_activity.datname = '$DB_NAME'
    AND pid <> pg_backend_pid();"

# Drop the database if it exists (connect to 'postgres' or another administrative database)
psql -h $DB_HOST -U $DB_USER -d postgres -c "DROP DATABASE IF EXISTS $DB_NAME;"

# Recreate the database (connect to 'postgres' or another administrative database)
psql -h $DB_HOST -U $DB_USER -d postgres -c "CREATE DATABASE $DB_NAME OWNER $DB_USER;"

echo "Database '$DB_NAME' has been dropped and recreated successfully."

# Run the SQL dump to build the database schema and other objects
psql -h $DB_HOST -U $DB_USER -d $DB_NAME -f opendata_schema.sql

echo "Database schema and data have been imported successfully."

# Unset the password and port after script execution for security reasons
unset PGPASSWORD
unset PGPORT
