#!/usr/bin/env sh
set -eu

cd "$(dirname "$0")"
exec php -S 0.0.0.0:${PORT:-3400} -t . router.php
