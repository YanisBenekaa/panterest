# Panterest

Panterest is a simply and friendly clone of Pinterest, enjoy it ! 

## Development environment

### Prerequisite

* PHP 7.4
* Composer
* Symfony CLI
* NPM (Node.js)
* MySQL
* Docker

You can check the prerequisite with the following command (of Symfony CLI) :

```Terminal
symfony check:requirements
```

### Installation

After cloning the project, you have to create an `.env.local` file at the root
of the project in which you'll set your `DATABASE_URL`, `MAILER_DSN`, `MYSQL_ROOT_PASSWORD`, 
`MYSQL_USER`, `MYSQL_PASSWORD` and `MYSQL_DATABASE`environment variables in this order :

- DATABASE_URL="mysql://user:password@panterest_db:3306/databaseName?serverVersion=5.7"
- MAILER_DSN=smtp://panterest_maildev:25
- MYSQL_ROOT_PASSWORD=password
- MYSQL_USER=user
- MYSQL_PASSWORD=password
- MYSQL_DATABASE=databaseName

After that, to have the project ready to work :

```Terminal
symfony composer install
npm i --force
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

You have to build the Webpack configuration with :

```Terminal
npm run build
```

### Launch development environment

```Terminal
symfony serve -d
symfony open:local
```

### Create Docker development environment

Before that, you have to check if the Docker Engine is working on your machine.

At the stage, you can install the images and create the containers with this following command
that will also start the containers :

```Terminal
docker-compose --env-file .env.local up --build
```

You need to check if you have the permissions to modify the files outside the containers.
If you're not `root`, you have to change the permissions from `root root` to `$currentUser root`
with the appropriate command (it depends on your system) like :

```Terminal
chown -R $USER ./
```

To launch the migrations, you need to enter inside the container that build the PHP image 
(it's panterest_web in this case) from another terminal : 

```Terminal
docker exec -it panterest_web bash
```

Now you can launch the migrations :

```Terminal
php bin/console doctrine:migrations:migrate
```

If everything is working, you can open the different interfaces of the application.

- http://127.0.0.1:8741 to open the web interface.

- http://127.0.0.1:8089 to open the phpmyadmin interface.

- http://127.0.0.1:8081 to open the maildev interface.

- http://127.0.0.1:9000 to open the portainer interface.