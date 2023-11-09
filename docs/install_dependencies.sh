#!/bin/bash

# Display Script Usage
usage() {
	echo "Usage: $0 [OPTIONS]"
	echo "Options:"
	echo " -h, --help   Display this help message"
	echo " -f, --front  Install Front-End Dependencies"
	echo " -b, --back   Install Back-End Dependencies"
	echo " -z, --dmz    Install DMZ Dependencies"
	echo " -d, --dev    Install Dev Dependencies"
	
has_argument() {
    [[ ("$1" == *=* && -n ${1#*=}) || ( ! -z "$2" && "$2" != -*)  ]];
}

extract_argument() {
  echo "${2:-${1#*=}}"
}

# Function to handle options and arguments
handle_options() {
  while [ $# -gt 0 ]; do
    case $1 in
	  -h | --help)
        usage
        exit 0
      -f | --front)
		sudo apt-get update -y
		sudo apt install apache2 php php-common php-amqp -y
        ;;
      -b | --back)
		sudo apt-get update -y
		sudo apt install mysql-common mysql-server php php-common php-amqp php-mysql -y
		## Install rabbitmq-server and its dependencies
		sudo apt-get install rabbitmq-server -y --fix-missing
		## Enable rabbitmq management plugin
		sudo rabbitmq-plugins enable rabbitmq_management
        ;;
      -z | --dmz)
		sudo apt-get update -y
        sudo apt install php php-common php-amqp -y
		;;
	  -d | --dev
	    sudo apt-get update -y
	    sudo apt install vim git ssh -y
        ;;
      )
        echo "Invalid option: $1" >&2
        usage
        exit 1
        ;;
    esac
    shift
  done
}

# Main script execution
handle_options "$@"
