#!/bin/bash
set -e
pushd $(dirname $(which $0))
target_PWD=$(readlink -f .)
echo ; echo “Please restart fppd for new FPP Commands to be visible.” ; echo
. /opt/fpp/scripts/common
# Remove log file left behind by versions before the plugin-*.log convention
[ -d "${LOGDIR}" ] && rm -f "${LOGDIR}/FPP-Simple-Countdown.log" || true
setSetting restartFlag 1
popd
