# Dvd Rental

DVD Rental application concept.

## Start Application
```bash
docker-compose up -d
```
The applicatin is accesible in http://localhost:8000 address.  
Demo user: test@domain.com  
Demo pass: 12345678

You can access to backoffice in http://localhost:8000/admin address.  
Demo user: testadmin@domain.com  
Demo pass: 12345678

## Initialize database
```bash
docker-compose exec php php bin/console doctrine:migrations:migrate
docker-compose exec php php bin/console doctrine:fixtures:load
```

## Run Tests
**Run end-to-end, functional and unit tests**
```bash
docker-compose exec php php bin/phpunit
```
**Run end-to-end tests**
```bash
docker-compose exec php php bin/phpunit --testsuite e2e
```
**Run integration tests**
```bash
docker-compose exec php php bin/phpunit --testsuite integration
```
**Run unit tests**
```bash
docker-compose exec php php bin/phpunit --testsuite unit
```
