#!/bin/bash
SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $SCRIPTDIR
bash mayfirst-composer-install.sh
cd $SCRIPTDIR/../prod
#if exists, remove opendata symlink
if [ -L opendata ]; then
  rm opendata 
fi
ln -s ../opendata/app opendata

