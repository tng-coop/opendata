#!/bin/bash
SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $SCRIPTDIR
bash mayfirst-composer-install.sh
prod=/home/members/yasuaki/sites/tng.coop/web/prod/opendata
#if exists, remove opendata symlink
if [ -L $prod ]; then
  rm $prod
fi
#symlink app to prod
ln -s $SCRIPTDIR/app $prod

