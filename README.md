## INIT shell
```
composer install
mkdir -p storage/logs storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views
chmod -R 777 storage
cp .env.example  .env
php artisan storage:link
php artisan key:generate
cd ./public/storage
mkdir uploadfile ueditor
php artisan migrate --database=majaic_math
php artisan db:init
```

## nginx.conf
```
user  nobody;
worker_processes  4;
error_log  /data/logs/error.log;
#error_log  logs/error.log  notice;
#error_log  logs/error.log  info;
#pid        logs/nginx.pid;
events {
    worker_connections  1024;
}

http {
    include       mime.types;
    default_type  application/octet-stream;

    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';

    access_log  /data/logs/access.log  main;

    sendfile        on;
	client_max_body_size 100m;
    keepalive_timeout  65;
    #gzip  on;
	fastcgi_connect_timeout 300;
	fastcgi_send_timeout 300;
	fastcgi_read_timeout 300;
	fastcgi_buffer_size 64k;
	fastcgi_buffers 4 64k;
	fastcgi_busy_buffers_size 128k;
	fastcgi_temp_file_write_size 128k;
	include /opt/local/etc/nginx/vhosts/*.conf;
}
```

## admin.conf

```

server {
    listen       80; 
    server_name  admin.com;
    root /laravel_admin/public;
    index index.html index.htm index.php;
    charset utf-8;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        include        fastcgi_params;
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_split_path_info       ^(.+\.php)(/.+)$;
        fastcgi_param PATH_INFO       $fastcgi_path_info;
        fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
    error_page 404 /404.html;
        location = /40x.html {
    }
    error_page 500 502 503 504 /50x.html;
        location = /50x.html {
    }
}   

```