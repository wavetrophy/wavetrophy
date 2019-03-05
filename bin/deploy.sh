#!/usr/bin/env bash
RELEASE=`date "+%Y%m%d_%H%M%S"`

cd ~/wavetrophy

mkdir -p ${RELEASE}
cd ${RELEASE}

# Update the repository
git clone git@github.com:wavetrophy/wavetrophy.git

# Copy
cp ../current/.env.local ./.env.local

# Install dependencies
composer install --prefer-dist --no-interaction --no-dev --optimize-autoloader --no-suggest

# Migrate database
bin/console do:mi:mi

# Warm up cache
bin/console c:w

cd ~/wavetrophy

ln -s `pwd`/${RELEASE} `pwd`/current