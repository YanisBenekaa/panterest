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
* Docker-Compose
* Traefik

You can check the prerequisite with the following command (of Symfony CLI) :

```Terminal
symfony check:requirements
```

### Installation

After cloning the project, you have to create an `.env.local` file at the root
of the project in which you'll set your `DATABASE_URL`, `MAILER_DSN`, `MYSQL_ROOT_PASSWORD`, 
`MYSQL_USER`, `MYSQL_PASSWORD` and `MYSQL_DATABASE`environment variables in this order :

If you want to use the project locally, the `DATABASE_URL`and `MAILER_DSN` environment variables will be 
like that following example :
- DATABASE_URL="mysql://user:password@127.0.0.1:3306/databaseName?serverVersion=5.7"
- MAILER_DSN=smtp://localhost:25

If you want to use it with the Docker configuration, you need to set this variables like that 
following example :
- DATABASE_URL="mysql://user:password@panterest_db:3306/databaseName?serverVersion=5.7"
- MAILER_DSN=smtp://panterest_maildev:25
- MYSQL_ROOT_PASSWORD=password
- MYSQL_USER=user
- MYSQL_PASSWORD=password
- MYSQL_DATABASE=databaseName

After that, to have the project ready to work :
(If you want to use the Docker configuration, you don't need to execute the five following commands.)

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

In the public folder, you have to create an uploads folder with inside a pins folder. It's vital to backup
the images of pins. This is the correct path : `public/uploads/pins`

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
USER_ID=$(id -u) GROUP_ID=$(id -g) docker-compose --env-file .env.local up -d --build
```

To launch the migrations, you need to enter inside the container that build the PHP image 
(it's panterest_web in this case) : 

```Terminal
docker exec -it panterest_web bash
```

Now you can launch the migrations :

```Terminal
php bin/console doctrine:migrations:migrate
```

### Create Traefik development environment

To access to the different interfaces of the application, you have to install locally the proxy server.
You can find it here : [server-proxy-portainer](https://github.com/YanisBenekaa/server-proxy-portainer)

If everything is working, you can open the different interfaces of the application.

- https://panterest.docker.localhost to open the web interface.

- https://pma-panterest.docker.localhost to open the phpmyadmin interface.

- http://127.0.0.1:8081 to open the maildev interface.