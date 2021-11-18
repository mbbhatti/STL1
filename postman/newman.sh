#!/usr/bin/env bash
which newman > /dev/null || {
    echo "please install newman"
    open https://www.npmjs.com/package/newman
    exit 1
}
(test -r "$2" && test -r "$3") || {
    cat <<EOF
$0 run a postman collection

Usage: $0 DEV|STAGING|LIVE <postman_collection.json> <postman_globals.json>

Example:

./newman.sh DEV "Eismann%20SMF%20Dev.postman_environment.json" "globals.postman_globals.json"
EOF
exit 1
}
export ENV="${2}"
export GLOBALS="${3}"
export COLLECTION="Universal-POS.postman_collection.json"
export JUNIT_OPTIONS="--reporter-junit-export junit-${1}.xml"


newman run --reporter cli,html,junit ${JUNIT_OPTIONS}  --globals "${GLOBALS}" --environment "${ENV}" "${COLLECTION}"
