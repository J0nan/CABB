# TFG - Certificates of Authenticity Based on Blockchain

## Getting Started

### Options for Windows environmets
``` sh
$ git config --global core.autocrlf false
```

### Setup
Create a .env file with the content of the .env.example and fill the empty spaces

``` sh
$ docker-compose up --build
```

### Code analysis
``` sh
# Run static code analysis
$ docker-compose exec app composer run analyse

# Generate code quality briefing
$ docker-compose exec app vendor/bin/phpinsights analyse
```

## Developing

This project is built on top of [Laravel](https://laravel.com)

## Git

How to use git within this project.