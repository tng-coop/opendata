#!/bin/bash

# Properly register command as import-db-to-mayfirst.sh

# Navigate to the directory containing opendata_schema.sql
cd ~/web/prod/opendata || exit

# Read the schema file, modify it, and import it to the database
cat opendata_schema.sql \
| sed '/CREATE TABLE public.opendata (/i DROP TABLE IF EXISTS public.opendata CASCADE;' \
| sed 's/OWNER TO yasu;/OWNER TO tng;/g' \
| sed '/default_table_access_method/d' \
| psql -U tng -d tng

# Confirm completion
echo "Database import completed successfully."
