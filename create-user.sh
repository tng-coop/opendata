#!/bin/bash

# Parse values from app.json
DB_HOST=$(jq -r '.database.host' app.json)
DB_NAME=$(jq -r '.database.dbname' app.json)  # Parse the database name
DB_USER=$(jq -r '.database.user' app.json)  # Parse the username to use for connecting
DB_PASSWORD=$(jq -r '.database.pass' app.json)
DB_PORT=$(jq -r '.database.port' app.json)
DB_NEW_USER=$(jq -r '.database.user' app.json)  # Take the same user as DB_USER for new user creation
DB_NEW_PASSWORD=$(jq -r '.database.pass' app.json)  # Use the same password as parsed

# Export password and port for non-interactive psql commands
export PGPASSWORD=$DB_PASSWORD
export PGPORT=$DB_PORT

# Create user if it doesn't already exist
psql -h $DB_HOST -U $DB_USER -d $DB_NAME -c "DO \$\$ BEGIN
  IF NOT EXISTS (SELECT FROM pg_catalog.pg_user WHERE usename = '$DB_NEW_USER') THEN
    CREATE USER $DB_NEW_USER WITH PASSWORD '$DB_NEW_PASSWORD';
    ALTER USER $DB_NEW_USER CREATEDB;
  END IF;
END \$\$;"

echo "User '$DB_NEW_USER' has been created or already exists."

# Unset the password and port after script execution for security reasons
unset PGPASSWORD
unset PGPORT
