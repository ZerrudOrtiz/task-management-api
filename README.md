## Task Management App

Task Management App is an API built using Laravel 11

#BACKEND 
  - LARAVEL 11 FRAMEWORK - PHP PHP 8.2

#DATABASE :
  -  MySQL

LOCAL PROJECT SETUP : 

1. Download WSL2 
LINK : https://learn.microsoft.com/en-us/windows/wsl/install-manual

2. Download LINUX OS (I USED UBUNTU IN MICROSOFT STORE)

3. RUN the UBUNTU

4. Update Package Lists:
	 - Run the following command to update the package lists:
		 sudo apt update

5. Download and Install PHP : 
	 - Choose the PHP version you want to install. In this case, I'll use PHP 8.2 as an example. You can replace it with the version you prefer:
		 sudo apt install php8.2-cli
	 - Verify PHP Installation:
	   php --version

5.1. Install Docker Compose in WSL:
   - sudo apt install docker-compose

6. Download and Install Composer:

	- Run the following commands in your WSL terminal to download and install Composer:
			php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
			php composer-setup.php
			php -r "unlink('composer-setup.php');"

	- Move Composer to a Global Location:
			sudo mv composer.phar /usr/local/bin/composer
	- Verify Installation:
		  composer --version


HOW TO RUN THE PROJECT


Clone the Repo inside the wsl - (FOR /task-management-application-api)
 - git clone https://github.com/ZerrudOrtiz/task-management-api.git

 - composer install 

open the project folder
 - code .
 - update .env file configuration (FOR /task-management-application-api)

open docker desktop

run the project
 - source .bashrc
 - sail up

Run the Database Migration and Seeder
 - sail artisan migrate
 - sail artisan db:seed

Run for Storage Link
 - sail artisan storage:link 

default user 
 - admin@taskmangement.ph
 - admin1234

VISIT ADMIN : ex: http://127.0.0.1:8080/admin/login

Run the Unit Test (phpunit)
 - sail artisan test

Run the Command to Delete task/Setup your cron scheduler
- sail artisan app:delete-old-tasks


# task-management-api
