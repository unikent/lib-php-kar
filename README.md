lib-php-kar
===========

[![Latest Stable Version](https://poser.pugx.org/unikent/lib-php-kar/v/stable.png)](https://packagist.org/packages/unikent/lib-php-kar)
[![Build Status](https://travis-ci.org/unikent/lib-php-kar.svg?branch=master)](https://travis-ci.org/unikent/lib-php-kar)

Full API docs available here: http://unikent.github.io/lib-php-kar/

PHP library for helping developers with KAR integrations

Add this to your composer require:
 * "unikent/lib-php-kar": "dev-master"

Then get lists like so:
```
$api = new \unikent\KAR\API('https://kar-test.kent.ac.uk');
$documents = $api->search_author("person@kent.ac.uk");
foreach ($documents as $document) {
    echo "---------------------------------\n";
    echo $document;
}
```
