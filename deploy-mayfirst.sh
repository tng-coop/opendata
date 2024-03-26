#!/bin/bash
SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $SCRIPTDIR
bash mayfirst-composer-install.sh
opendata_dir=/home/members/yasuaki/sites/tng.coop/web/prod/opendata
rm -rf $opendata_dir
mkdir -p $opendata_dir

rsync -av app/ $opendata_dir 
cp app-yasu.json $opendata_dir/app.json
cp .htaccess $opendata_dir 
