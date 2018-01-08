##Docker up
```
docker-compose build --pull
docker-compose up -d
```

##Install
```
docker-compose exec fpm bash
composer install
php artisan migrate
```

##Run tests
```
docker-compose exec fpm bash
./vendor/bin/phpunit ./tests/
```

##Entrypoints
```
http://localhost:85/
http://localhost:85/bad_domains/

http://localhost:85/click/?param1=123&param2=123
http://localhost:85/success/{CLICK_ID}
http://localhost:85/error/{CLICK_ID}
```