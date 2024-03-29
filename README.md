# Running

to run the application clone the repository than run composer install
````
composer install
````

once the install is completed run
````
./start.sh
````

the script will setup jwt cerficates for later authentication, generate db and update schema, create a User for you 
(the command will ask for email and password) and start a symfony built in server

after the script finishes it will print server's url for. to view API docs visit
````
/api/doc
````
for swagger documentation.

First thing you have to do is to get a JWT token by calling `/api/authorize` with the credentials you used for creating 
user. in turn you receive a token that has to be user for every subsequent request in header `Authorization: {token}`.
it can also be pasted into swagger's authorize popup for testing requests with supplied frontend

## Running test

tests can be run after comproser install by calling:
````
bin/phpunit --configuration phpunit.unit.xml
````

# Description
Create a tiny RESTful web service with the following business requirements:

## Application must expose REST API endpoints for the following functionality:

* create product (price, productType, color, size)
* calculate order price (Collection of products and quantities)  (should also save Order draft somewhere)
* list all Orders
* list all Orders by productType

## Service must perform operation validation according to the following rules and reject if:

* type + color + size already exists
* Order is empty or total price is less than 10
* N orders / second are received from a single country (essentially we want to limit number of orders coming from a country in * a given timeframe)

Service must perform origin country resolution using a web service and store country code together with the order draft.
Because network is unreliable and services tend to fail, let's agree on default country code - "US".

## Technical requirements:

* You have total control over tools, as long as application is written in PHP and Laravel or Symfony framework.

## What gets evaluated:

* Conformance to business requirements
* Code quality, including testability
* How easy it is to run and deploy the service (don't make us install Oracle database please 😉
* Good luck and have fun!
