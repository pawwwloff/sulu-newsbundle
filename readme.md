# Sulu news bundle


## Requirements

* PHP >= 7.4
* Sulu >= 2.4.*
* Symfony >= 5.4

## Features
* List view of News (smart content)
* Without elasticsearch
* Routing
* Preview
* SULU Media include
* Content Blocks (Title,Editor,Image,Quote)
* Activity Log
* Trash
* Automation
* SEO


## Installation

### Install the bundle

Execute the following [composer](https://getcomposer.org/) command to add the bundle to the dependencies of your
project:

```bash

composer require pixeldev/sulu-newsbundle --with-all-dependencies

```

### Enable the bundle

Enable the bundle by adding it to the list of registered bundles in the `config/bundles.php` file of your project:

 ```php
 return [
     /* ... */
     Pixel\NewsBundle\NewsBundle::class => ['all' => true],
 ];
 ```

### Update schema
```shell script
bin/console do:sch:up --force
```

## Bundle Config

Define the Admin Api Route in `routes_admin.yaml`
```yaml
news.news_api:
  type: rest
  prefix: /admin/api
  resource: pixel_news.news_route_controller
  name_prefix: news.
```

