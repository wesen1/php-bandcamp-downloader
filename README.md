php-bandcamp-downloader
=======================

A simple bandcamp album downloader written in PHP


Dependencies
------------

* php7.0
* php7.0-curl
* php7.0-mbstring
* composer


Installation
------------

### Manual Installation ###

* Install the dependencies
* Navigate to the main folder of this repository and use ````composer install````

### Vagrant ###

* Install virtualbox
* Navigate to the main folder of this repository and use ````vagrant up````
* When the vagrant box build is finished use ````vagrant ssh````
* Inside the vagrant box type ````cd /vagrant````


Usage
-----

php main.php <url to bandcamp album>
