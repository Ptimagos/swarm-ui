#!/bin/bash


function check_directory_exists () {

	if [[ -d $1 ]]; then
		log_ok "+ The directory $1 exists."
	else
		log_error "+ The directory $1 doesn't exist."
		exit 1;
	fi
}

function check_file_exists () {

	if [[ -f $1 ]]; then
		log_ok "+ The file $1 exists."
	else
		log_error "+ The file $1 doesn't exist."
		exit 1;
	fi
}
