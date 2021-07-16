# Panterest

Panterest is a simply and friendly clone of Pinterest, enjoy it ! 

## Development environment

### Prerequisite

* PHP 7.4
* Composer
* Symfony CLI
* NPM
* MySQL (or another database)

You can check the prerequisite with the following command (of Symfony CLI) :

```Terminal
symfony check:requirements
```

### Installation

After cloning the project, you have to create an `.env.local` file at the root
of the project in which you'll write your `DATABASE_URL` and `MAILER_DSN` environment
variables.

After that, to have the project ready to work :

```Terminal
symfony composer update
npm i --force
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

You can check if Webpack build correctly the CSS and JS file with :

```Terminal
npm run build
```

### Launch development environment

```Terminal
symfony serve -d
```

