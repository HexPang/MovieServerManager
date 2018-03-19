# 电影下载机
一个电影下载管理系统，适合放在树莓派上。使用Laravel开发

> 已针对新的网站进行了调整

## 需求
1. Aria2
2. php*-curl
3. php*-ssh2
4. composer

## 配置信息(.env)
```
SERVER_HOST=localhost
SERVER_PORT=22
SERVER_USERNAME=pi
SERVER_PASSWORD=rsapberrypi
SERVER_ARIA=http://localhost:6800/jsonrpc
```

## 安装方式
1. git clone
2. composer update
3. copy .env.example to .env
4. php artisan key:gen

## 截图
![1](https://github.com/HexPang/MovieServerManager/raw/master/screenshots/1.png)

![2](https://github.com/HexPang/MovieServerManager/raw/master/screenshots/2.png)

![3](https://github.com/HexPang/MovieServerManager/raw/master/screenshots/3.png)

![4](https://github.com/HexPang/MovieServerManager/raw/master/screenshots/4.png)
