##Docker Drupal testbots on your local box!

###Test your Drupal patches locally with docker.

This repo contains a recipe for making a [Docker](http://docker.io) containers for Drupal testing, using Linux, Apache, PHP and MySQL/sqlite. 
To build, make sure you have Docker [installed](http://www.docker.io/gettingstarted/).

This will also in line with [Drupal automated-testing](https://drupal.org/automated-testing).


### 1- Install docker:
```
curl get.docker.io | sudo sh -x
```

### 2- Clone this repo somewhere, 
```
git clone {thisrepo}
cd modernizing_testbot__dockerfiles
```
### 3- Build the database image 
```
cd distributed/database/mysql
sudo ./build.sh 
```
### 4- Start the DB container and check it's running on port 3306
```
sudo ./run-server.sh 
sudo docker ps
```

### 5- Build the WEB image
```
cd ~/modernizing_testbot__dockerfiles
cd distributed/apachephp
sudo ./build.sh 5.4
```
### 6- Eg. To run a web container with all tests using 2 cpu:
```
sudo TESTGROUPS="--all" CONCURRENCY="2" ./run.sh 
```
And that's it.


### Some default environment variables

```
DRUPALVERSION="7.26"
DRUPALBRANCH="7"
CONCURRENCY="4" #How many cpus to use per run
TESTGROUPS="--class NonDefaultBlockAdmin" #TESTS TO RUN
RUNSCRIPT="php ./scripts/run-tests.sh --php /usr/bin/php --url 'http://localhost' --color --concurrency ${CONCURRENCY} --xml '/var/workspace/results' ${TESTGROUPS} "
PHPVERSION="5.4"
PATCH=""
DBTYPE="mysql"
```

If you need to remove the old web image just run this sequence:
```
sudo docker images | grep "drupal/testbot-web"
sudo docker rmi {imageID}
```

### Clean up all 
While i am developing i use this to rm all old instances
```
sudo docker ps -a | awk '{print $1}' | xargs -n1 -I {} sudo docker rm {}
``` 

## Current Structure:
```
├── distributed
│   ├── apachephp
│   │   ├── build.sh
│   │   ├── conf
│   │   │   ├── apache2
│   │   │   │   └── vhost.conf
│   │   │   ├── php5
│   │   │   │   ├── apache2.ini
│   │   │   │   ├── apc.ini
│   │   │   │   └── cli.ini
│   │   │   ├── scripts
│   │   │   │   ├── foreground.sh
│   │   │   │   └── start.sh
│   │   │   └── supervisor
│   │   │       └── supervisord.conf
│   │   ├── Dockerfile -> Dockerfile-PHP5.4
│   │   ├── Dockerfile-PHP5.3
│   │   ├── Dockerfile-PHP5.4
│   │   ├── Dockerfile-PHP5.5
│   │   └── run.sh
│   └── database
│       └── mysql
│           ├── build.sh
│           ├── Dockerfile
│           ├── run-client.sh
│           ├── run-server.sh
│           └── startup.sh
├── README.md
└── supervisord
    ├── build.sh
    ├── default
    ├── Dockerfile
    ├── php-cli.ini
    ├── php.ini
    ├── run.sh
    └── supervisord.conf
```

## Contributing
Feel free to fork and contribute to this code. :)

1. Fork the repo
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Added some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request
