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

while [[ ${OPT} != "--" ]]; do
	OPT=${1}
	shift
done

OUTPUT="$("${SCRIPT_PATH}/vaultenv.php" --token="${VAULT_TOKEN}" --addr="${VAULT_ADDR}" --key="${VAULT_KEY}")"

eval ${OUTPUT}


unset VAULT_ADDR
unset VAULT_TOKEN
unset VAULT_KEY

exec "${@}"
