WAVE Trophy Application
==========

The Application for the WAVE Trophy Rally.
Further information can be found at [the website](https://wavetrophy.com)

Requirements
============

- Apache >= 2.4
- PHP >= 7.1.13
- Composer (latest)
- Git (latest)
- gRPC

Instalation
===========

- Clone this repository
- From the command-line:

```
:~$ cd wavetrophy
:~$ composer install
:~$ bin/console doctrine:database:create
:~$ bin/console doctrine:schema:update --force
:~$ bin/console fos:user:create admin admin@example.com admin --super-admin
```

How to run it?
==============

You got two options:

1. [Configure an apache site for the project](docs/configure_jwtxample_site_in_apache.md)
2. From the command-line:

```
:~$ bin/console server:run
```