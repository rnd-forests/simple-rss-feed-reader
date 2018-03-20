# RSS Feed Reader

## Installation

We use Docker and Docker Compose for constructing the development environment of the application. Therefore, we first need to install these two softwares:

- Install Docker: https://docs.docker.com/install
- Install Docker Compose: https://docs.docker.com/compose/install

Make sure `docker` and `docker-compose` commands are available in current shell.

In order to run `docker` command without **sudo** privilege:
- Create **docker** group if it hasn't existed yet: `sudo groupadd docker`
- Add current user to **docker** group: `sudo gpasswd -a ${whoami} docker`
- You may need to logout in order to these changes to take effect.

Change current directory to application code folder and run the following commands:
- Copy file `.env.example` to `.env`
- Start up docker containers: `docker-compose up -d`
- Change to workspace environment: `docker exec -it rss_workspace bash`

Inside workspace container, run the following commands:
- Install composer packages: `composer install --no-suggest`
- Install the application: `php artisan feed:install`
- Change permission for some directories: `chmod -R 777 storage/ bootstrap/`
- Seed the database: `php artisan db:seed`

The default database credentials for different environments (database, username, password):
- Local environment: **homestead**, **homestead**, **secret**
- Testing environment: **homestead_test**, **homestead**, **secret**

If you update these environment variables, you must restart docker containers in order to those changes to take effect.

By default, port 80 of NGINX container is mapped to port 8000 of the host machine. If this port is currently used by another application, you can change that port by editing `docker-compose.yml`.

Some tested feeds:
- Laravel News: https://feed.laravel-news.com
- Viblo: https://viblo.asia/rss
- Laracasts: https://laracasts.com/feed
- Ansible Blog: https://www.ansible.com/blog/rss.xml
- VNExpress Law: https://vnexpress.net/rss/phap-luat.rss
- VNExpress Sport: https://vnexpress.net/rss/the-thao.rss
- Vue.js Feed: https://vuejsfeed.com/feed

## Demo

**CLI**

[![asciicast](https://asciinema.org/a/qozB1UQBx3t6YWD6xGum9qNAl.png)](https://asciinema.org/a/qozB1UQBx3t6YWD6xGum9qNAl)

**GUI**

https://www.youtube.com/watch?v=hcewleRcoAs
