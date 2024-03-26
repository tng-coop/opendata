#!/bin/bash
SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $SCRIPTDIR
bash mayfirst-composer-install.sh
cd $SCRIPTDIR/app
#if exists, remove opendata symlink
if [ -L app.json ]; then
  rm app.json 
fi
#symlink app to prod
ln -s ../app-mayfirst.json app.json
cd $SCRIPTDIR/../prod
#if exists, remove opendata symlink
if [ -L opendata ]; then
  rm opendata 
fi
ln -s ../opendata/app opendata

