test-marfeel
====

The project is made with Symfony 3.4

## Setup the Project

Please make sure to have docker and docker-compose installed on your local machine before proceeding

Please run the following commands

```bash
docker-compose up -d
```

```bash
docker exec -it -u www-data test-marfeel_php composer install --no-interaction
```

```bash
yes | docker exec -u www-data test-marfeel_php php bin/console doc:mig:mig
```

## Testing the API

Exposed URI is localhost:8080/api/v1/browse and expects json to be sent via POST raw data

To test with unit test

```bash
docker exec -it -u www-data test-marfeel_php ./vendor/bin/simple-phpunit
```
