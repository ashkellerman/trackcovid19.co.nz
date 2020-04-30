#!/usr/bin/env bash

# Typography
COLOR_RED='\033[1;32m'
COLOR_INITIAL='\033[0m'

SRC_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="$SRC_DIR/../"
DEST_DIR="/home/trackcovid/container/application/"
DEST_HOST="120.138.30.73"
DEST_DOMAIN="trackcovid19.co.nz"
DEST_USER="trackcovid"
DEST_PATH="$DEST_USER@$DEST_HOST:$DEST_DIR"

ENV_FILE="$PROJECT_DIR/config/$ENV_NAME/.env"
ROBOTS_FILE="$PROJECT_DIR/config/$ENV_NAME/robots.txt"
HTACCESS_FILE="$PROJECT_DIR/config/$ENV_NAME/.htaccess"
SYSTEM_FILE="$PROJECT_DIR/config/$ENV_NAME/system.yaml"
DEBUG_FILE="$PROJECT_DIR/config/$ENV_NAME/debug.yaml"

# Pre-deployment
pushd $PROJECT_DIR > /dev/null
printf "${COLOR_RED}> Compiling static assets${COLOR_INITIAL}\n"
cd $PROJECT_DIR/ && npm run prod

# Sync project files
printf "${COLOR_RED}> Syncing files between local and server ($DEST_DOMAIN)${COLOR_INITIAL}\n"

cd $PROJECT_DIR/
# Sync core root directory files
rsync --compress --recursive --checksum --info=progress2 .env $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 artisan $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 composer.json $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 composer.lock $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 package-lock.json $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 package.json $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 phpunit.xml $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 README.md $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 server.php $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 tailwind.config.js $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 webpack.mix.js $DEST_PATH

# Sync Statamic website files
rsync --compress --recursive --checksum --info=progress2 app $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 bootstrap $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 config $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 database $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 node_modules $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 public $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 resources $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 routes $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 storage $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 tests $DEST_PATH
rsync --compress --recursive --checksum --info=progress2 vendor $DEST_PATH


popd > /dev/null

# Run DB migrations, and flush cache and templates
printf "${COLOR_RED}> Running DB migrations, and flushing cache and templates${COLOR_INITIAL}\n"
ssh $DEST_USER@$DEST_HOST "cd $DEST_DIR ; php artisan migrate"
ssh $DEST_USER@$DEST_HOST "cd $DEST_DIR ; php artisan cache:clear"

# Success!
printf "${COLOR_RED}> Deployment complete${COLOR_INITIAL}\n"
