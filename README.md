# Movie Server Manager
A PHP Movie Server Web Manager.

## Required
1. Aria2
2. php*-curl
3. php*-ssh2
4. composer

## Environment(.env)
```
SERVER_HOST=localhost
SERVER_PORT=22
SERVER_USERNAME=pi
SERVER_PASSWORD=rsapberrypi
SERVER_ARIA=http://localhost:6800/jsonrpc
```

## Installation
1. Clone
2. composer update
3. copy .env.example to .env
4. php artisan key:gen

## Screenshots
![1](https://github.com/HexPang/MovieServerManager/raw/master/screenshots/1.png)

![2](https://github.com/HexPang/MovieServerManager/raw/master/screenshots/2.png)

![3](https://github.com/HexPang/MovieServerManager/raw/master/screenshots/3.png)

![4](https://github.com/HexPang/MovieServerManager/raw/master/screenshots/4.png)
