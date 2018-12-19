#!/usr/bin/env bash

set -e

# Installing required libraries via Phive
phive install

./tools/php-cs-fixer fix -v --dry-run
./tools/phpstan analyze --level=7 src/ src-kirby-client/ src-solarium-clustering/
