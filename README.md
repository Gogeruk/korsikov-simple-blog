# My Simple Blog Website

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