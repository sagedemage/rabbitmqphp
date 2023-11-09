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
		sudo apt install apache2 php php-common php-amqp -y
        ;;
      -b | --back)
		sudo apt install mysql-common mysql-server php php-common php-amqp php-mysql -y
		sudo apt-get install curl gnupg apt-transport-https -y
		## Team RabbitMQ's main signing key
		curl -1sLf "https://keys.openpgp.org/vks/v1/by-fingerprint/0A9AF2115F4687BD29803A206B73A36E6026DFCA" | sudo gpg --dearmor | sudo tee /usr/share/keyrings/com.rabbitmq.team.gpg > /dev/null
		## Community mirror of Cloudsmith: modern Erlang repository
		curl -1sLf https://github.com/rabbitmq/signing-keys/releases/download/3.0/cloudsmith.rabbitmq-erlang.E495BB49CC4BBE5B.key | sudo gpg --dearmor | sudo tee /usr/share/keyrings/rabbitmq.E495BB49CC4BBE5B.gpg > /dev/null
		## Community mirror of Cloudsmith: RabbitMQ repository
		curl -1sLf https://github.com/rabbitmq/signing-keys/releases/download/3.0/cloudsmith.rabbitmq-server.9F4587F226208342.key | sudo gpg --dearmor | sudo tee /usr/share/keyrings/rabbitmq.9F4587F226208342.gpg > /dev/null
## Add apt repositories maintained by Team RabbitMQ
sudo tee /etc/apt/sources.list.d/rabbitmq.list <<EOF
## Provides modern Erlang/OTP releases
##
deb [signed-by=/usr/share/keyrings/rabbitmq.E495BB49CC4BBE5B.gpg] https://ppa1.novemberain.com/rabbitmq/rabbitmq-erlang/deb/ubuntu jammy main
deb-src [signed-by=/usr/share/keyrings/rabbitmq.E495BB49CC4BBE5B.gpg] https://ppa1.novemberain.com/rabbitmq/rabbitmq-erlang/deb/ubuntu jammy main

# another mirror for redundancy
deb [signed-by=/usr/share/keyrings/rabbitmq.E495BB49CC4BBE5B.gpg] https://ppa2.novemberain.com/rabbitmq/rabbitmq-erlang/deb/ubuntu jammy main
deb-src [signed-by=/usr/share/keyrings/rabbitmq.E495BB49CC4BBE5B.gpg] https://ppa2.novemberain.com/rabbitmq/rabbitmq-erlang/deb/ubuntu jammy main

## Provides RabbitMQ
##
deb [signed-by=/usr/share/keyrings/rabbitmq.9F4587F226208342.gpg] https://ppa1.novemberain.com/rabbitmq/rabbitmq-server/deb/ubuntu jammy main
deb-src [signed-by=/usr/share/keyrings/rabbitmq.9F4587F226208342.gpg] https://ppa1.novemberain.com/rabbitmq/rabbitmq-server/deb/ubuntu jammy main

# another mirror for redundancy
deb [signed-by=/usr/share/keyrings/rabbitmq.9F4587F226208342.gpg] https://ppa2.novemberain.com/rabbitmq/rabbitmq-server/deb/ubuntu jammy main
deb-src [signed-by=/usr/share/keyrings/rabbitmq.9F4587F226208342.gpg] https://ppa2.novemberain.com/rabbitmq/rabbitmq-server/deb/ubuntu jammy main
EOF
		## Update package indices
		sudo apt-get update -y

		## Install Erlang packages
		sudo apt-get install -y erlang-base \
								erlang-asn1 erlang-crypto erlang-eldap erlang-ftp erlang-inets \
								erlang-mnesia erlang-os-mon erlang-parsetools erlang-public-key \
								erlang-runtime-tools erlang-snmp erlang-ssl \
								erlang-syntax-tools erlang-tftp erlang-tools erlang-xmerl

		## Install rabbitmq-server and its dependencies
		sudo apt-get install rabbitmq-server -y --fix-missing

		## Install essential dependencies
		sudo apt-get install curl gnupg -y

		## Enable apt HTTPS Transport
		sudo apt-get install apt-transport-https
		sudo apt-get upgrade -y

		## Enable rabbitmq management plugin
		sudo rabbitmq-plugins enable rabbitmq_management

		## Confirm rabbitmq server status
		sudo systemctl status rabbitmq-server
        ;;
      -z | --dmz)
        sudo apt install php php-common php-amqp -y
		;;
	  -d | --dev
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