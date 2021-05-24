# Attributes router

[![Build Status](https://travis-ci.com/Eredost/attributes-router.svg?branch=main)](https://travis-ci.com/Eredost/attributes-router)
[![Maintainability](https://api.codeclimate.com/v1/badges/73fa249c8e3ddb42263c/maintainability)](https://codeclimate.com/github/Eredost/attributes-router/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/73fa249c8e3ddb42263c/test_coverage)](https://codeclimate.com/github/Eredost/attributes-router/test_coverage)

Attributes router is a light library allowing to set up a router and to define routes via the attributes of PHP 8.

## Installation

1. First, as the library is not referenced on Packagist, you will have to add the repository in the configuration of the composer.json

   ```json
   "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/Eredost/attributes-router.git"
      }
   ]
   ```

2. Then, all you have to do is install the library using the following command:

   ```shell
   composer require eredost/attributes-router:dev-main
   ```
