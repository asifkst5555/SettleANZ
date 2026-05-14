#!/usr/bin/env bash
set -euo pipefail

cd /home/asifk/projects/SettleANZ

export NVM_DIR="$HOME/.nvm"

if [[ ! -s "$NVM_DIR/nvm.sh" ]]; then
  echo "nvm is not installed. Run scripts/start-vite-wsl.sh first."
  exit 1
fi

# shellcheck source=/dev/null
. "$NVM_DIR/nvm.sh" --no-use

nvm use 22
exec npm run dev -- --host 0.0.0.0
