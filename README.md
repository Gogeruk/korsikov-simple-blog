# My Simple Blog Website

If evetything is good, it should be running at [https://korsikov.com](https://korsikov.com)

I created this blog website for several reasons:

1. **Freedom of Expression**: I got tired of moderation on various forums, image boards, and obscure Discord servers. I simply wanted a place where I can do whatever I want whenever I want.
2. **Support for Personal Websites**: I believe the internet should consist of websites like this, instead of everyone using two or three major social media sites.
3. **Fun**: I am just having some fun.

## Getting Started

Follow these steps to set up the project:

### 1. Clone the Repository
```bash
git clone https://github.com/Gogeruk/korsikov-simple-blog
```

### 2. Navigate to the Directory
```bash
cd korsikov-simple-blog/
```

### 3. Start the Containers
```bash
docker-compose up -d
```

### 4. Get the Container ID of the PHP Container
```bash
docker ps
```

### 5. Enter the Container
```bash
docker exec -it <container_id> /bin/bash
```

### 6. Install Dependencies
```bash
composer install
```

### 7. Clear the Cache (Just in Case)
```bash
php bin/console cache:clear --env=prod
rm -r var/cache/*
```

### 8. Change the Owner of the `var` Directory
```bash
chown -R www-data:www-data var
chmod -R 775 var
```

### 9. Generate the Cache
```bash
php bin/console cache:warmup --env=prod
```

### 10. Delete it again! MUHAHAHAHA!
```bash
rm -r var/cache/*
```

### 11. Access the Website
[http://localhost:8080/](http://localhost:8080/)


# For dev environment #
docker-compose.yml
```
version: '3.7'

services:

  php-blog:
    container_name: php-blog
    build:
      context: ./php
    ports:
      - '9001:9000'
    volumes:
      - ./app:/var/www/project
      - ./php/php.ini:/usr/local/etc/php/php.ini
      - ./php/conf.d/xdebug.ini:/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
      - ./php/conf.d/error_reporting.ini:/usr/local/etc/php/conf.d/error_reporting.ini
    networks:
      - app-network

  nginx-blog:
    container_name: nginx-blog
    image: nginx:stable-alpine
    ports:
      - '8080:80'
    volumes:
      - ./app:/var/www/project
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php-blog
    networks:
      - app-network

networks:
  app-network:
    driver: bridge
```
and this is the default.conf file
```
server {
    listen 80;
    server_name localhost;
    root /var/www/project/public;

    index index.php index.html;

    error_log /var/log/nginx/project_error.log;
    access_log /var/log/nginx/project_access.log;

    client_body_timeout 600s;
    client_header_timeout 600s;
    client_max_body_size 10M;
    client_body_buffer_size 10M;
    large_client_header_buffers 4 16k;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        include fastcgi_params;
        fastcgi_pass php-blog:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;

        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    location ~ \.php$ {
        try_files $uri =404;
        include fastcgi_params;
        fastcgi_pass php-blog:9000;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;

        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    location ~ /\.ht {
        deny all;
    }
}
```