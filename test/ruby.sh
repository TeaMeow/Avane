#!/bin/bash

curl -O https://rvm.io/mpapis.asc
gpg --import mpapis.asc
curl -sSL https://get.rvm.io | bash -s stable
source /etc/profile.d/rvm.sh
rvm requirements
rvm install ruby