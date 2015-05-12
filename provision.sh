#!/bin/bash -e
#
# Name:         provision.sh
#
# Purpose:      quick start the vagrant box with all the things
#
# Comments:
#
# Usage:        vagrant up (on the repo root)
#
# Author:       Ricardo Amaro (mail_at_ricardoamaro.com)
# Contributors: Jeremy Thorson jthorson
# Contributors: Rudy Grigar (basic)
#
# Bugs/Issues:  Use the issue queue on drupal.org
#               IRC #drupal-testing
#
# Docs:         README.md for complete information
#

export HOME="/home/vagrant"

if [ -f /home/vagrant/drupalci_testbot/PROVISIONED ];
then
	echo "--------------------------------------------------------------------"
	echo
	echo "######                                      #####  ###"
	echo "#     # #####  #    # #####    ##   #      #     #  # "
	echo "#     # #    # #    # #    #  #  #  #      #        # "
	echo "#     # #    # #    # #    # #    # #      #        # "
	echo "#     # #####  #    # #####  ###### #      #        # "
	echo "#     # #   #  #    # #      #    # #      #     #  # "
	echo "######  #    #  ####  #      #    # ######  #####  ###   TESTBOT"
	echo ""
	echo "--------------------------------------------------------------------"
	echo
	echo "Hi there, it is your local Testbot!"
	echo
	echo "You seem to have this box already installed - which is a good thing!"
	echo "Documentation can be found in README.md or read on..."
	echo ""
else
	echo 'cd /home/vagrant/drupalci_testbot' >> /home/vagrant/.bashrc
	echo 'Defaults        env_keep +="HOME"' >> /etc/sudoers
	echo "Installing and building the all the things..."
	echo "on: $(hostname) with user: $(whoami) home: $HOME"
	swapoff -a
	dd if=/dev/zero of=/var/swapfile bs=1M count=2048
	chmod 600 /var/swapfile
	mkswap /var/swapfile
	swapon /var/swapfile
	/bin/echo "/var/swapfile swap swap defaults 0 0" >>/etc/fstab
	apt-get update && apt-get upgrade -y
	apt-get install -y git mc ssh gawk grep sudo htop mysql-client php5-cli curl php5-curl \
         mysql-client postgresql-client postgresql-client-common
	apt-get autoclean
        echo "Installing docker"
        curl -s get.docker.io | sh 2>&1 | egrep -i -v "Ctrl|docker installed"
        usermod -a -G docker vagrant
	cd /home/vagrant/drupalci_testbot
  echo "Installing composer"
  curl -sS https://getcomposer.org/installer | php
  echo "Running php composer.phar update"
  php composer.phar update
  echo "Creating drupalci symlink"
  ln -s /home/vagrant/drupalci_testbot /opt/drupalci_testbot
  mkdir -p /var/lib/drupalci/web
  mkdir -p /var/lib/drupalci/database/mariadb-5.5
  mkdir -p /var/lib/drupalci/database/mariadb-10.0
  mkdir -p /var/lib/drupalci/database/mysql-5.5
  mkdir -p /var/lib/drupalci/database/pgsql-9.1
  mkdir -p /var/lib/drupalci/database/pgsql-9.4
  chown -R 102:102 /var/lib/drupalci/database
	touch PROVISIONED
fi

chown -fR vagrant:vagrant /home/vagrant
echo "Box started up, run *vagrant halt* to stop."
echo
echo "To access the box and run tests, run:"
echo "- vagrant ssh"
