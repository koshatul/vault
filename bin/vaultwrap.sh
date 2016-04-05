#!/bin/bash

SCRIPT_PATH="$(cd "$(dirname "${0}")"; pwd)"

while getopts ":t:a:k:" OPT; do
	case ${OPT} in
		t)
			VAULT_TOKEN="${OPTARG}"
			;;
		a)
			VAULT_ADDR="${OPTARG}"
			;;
		k)
			VAULT_KEY="${OPTARG}"
			;;
		*)
			echo "Unknown Option: ${OPT}"
			;;
	esac
done

while [[ ${OPT} != "--" && "${#}" -gt "0" ]]; do
	OPT=${1}
	shift
done

if [[ "${#}" -lt "1" ]]; then
	echo "usage: ${0} [-t <token>] [-a <address>] [-k <key>] -- command arguments" >&2
	exit 99
fi

OUTPUT="$("${SCRIPT_PATH}/vaultenv.php" --token="${VAULT_TOKEN}" --addr="${VAULT_ADDR}" --key="${VAULT_KEY}")"
RV="${?}"


if [[ ${RV} != "0" ]]; then
	echo "error: error running vaultenv.php, is the token, address and key set correctly ?" >&2
	exit 99
fi

eval "${OUTPUT}"


unset VAULT_ADDR
unset VAULT_TOKEN
unset VAULT_KEY

exec "${@}"
