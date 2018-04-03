#!/usr/bin/env bash

cd /var/working/selenium-setup
php selenium-setup start worldclass
cd /var/working/worldclass
./vendor/bin/behat --config ./src/Tests/Config/behat.yml.example ./src/Tests/Features/worldclassbooking.feature
