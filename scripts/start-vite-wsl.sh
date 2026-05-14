#!/usr/bin/env bash
set -euo pipefail

cd /home/asifk/projects/SettleANZ

export NVM_DIR="$HOME/.nvm"

if [[ ! -s "$NVM_DIR/nvm.sh" ]]; then
  curl -fsSL https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash
fi

# shellcheck source=/dev/null
. "$NVM_DIR/nvm.sh" --no-use

nvm install 22
nvm use 22
npm install

nohup npm run dev -- --host 0.0.0.0 > /tmp/settleanz-vite.log 2>&1 &
echo "$!"
