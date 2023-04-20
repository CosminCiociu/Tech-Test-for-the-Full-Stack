# Symfony + Vite + Inertia + Vuejs 3

This is a template for Symfony project with Inertia and Vuejs that uses Vite instead of Webpack

## Using this template

Follow this instruction in creating new repo from this template
https://docs.github.com/en/repositories/creating-and-managing-repositories/creating-a-repository-from-a-template

## Requirements

- PHP 8
- Composer
- Symfony CLI
- Node
- NPM

## Installation and Running

Before you do the steps, make sure you are installed PHP8, Symfony, Composer, NodeJs and NPM.

1. Edit .env file

```shell
DATABASE_URL="mysql://Cosmin:welcome01@127.0.0.1:3306/cosmin"
```

2. Install PHP Packages using Composer

```shell
composer install
```

3. Next install your node packages using npm

```shell
npm install
```

4. Create Schema database

```shell
symfony console doctrine:schema:create
```

5. Populate database with orders.json

```shell
symfony console populate-db
```

6. Run node server for frontend dev env

```shell
npm run dev
```

7. Run symfony server for backend

```shell
symfony server:start
```

8. Access http://localhost:8000/orders in your browser to see the table

## References

- [Symfony](https://symfony.com/)
- [Symfony Inertia Bundle](https://github.com/rompetomp/inertia-bundle)
- [Symfony Vite Bundle](https://github.com/lhapaipai/vite-bundle)
- [Inertia Js](https://inertiajs.com/)
