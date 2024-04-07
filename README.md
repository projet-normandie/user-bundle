ProjetNormandieUserBundle
===========================

Develop
-------

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/projet-normandie/user-bundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/projet-normandie/user-bundle/?branch=develop)
[![Build Status](https://scrutinizer-ci.com/g/projet-normandie/user-bundle/badges/build.png?b=develop)]()



Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require projet-normandie/user-bundle
```



### Configuration

config/pachages/gesdinet_jwt_refresh_token.yaml

```yaml
gesdinet_jwt_refresh_token:
    refresh_token_class: ProjetNormandie\UserBundle\Entity\RefreshToken
```