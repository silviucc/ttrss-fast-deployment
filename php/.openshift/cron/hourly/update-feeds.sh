#!/bin/bash

$OPENSHIFT_REPO_DIR/php/update.php -feeds >/dev/null 2>&1
date >> $OPENSHIFT_DATA_DIR/logs/update-feeds.log
