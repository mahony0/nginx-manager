# Nginx Manager

This is an Nginx webserver manager script which provides powerful tools for server management

> Project is in work in progress (WIP) state, use at your own risk on prod servers!

*PRs would be gladly accepted*


## Capabilities

- Useful dashboard statistics for easy **service status** check
- Can detect both **conf.d** and **sites-available/sites-enabled** setups
- Nginx **main configuration files** editing
- **Domain template** stubs for very easy domain setups
- Easily **enable/disable** any domain or **create** new one
- **Reload** Nginx service
- Easily **Restart** following services:
    - Nginx
    - Apache
    - Postfix Mail Service
    - Dovecot IMAP/POP3 Service
    - vsftpd FTP Service
    - ProFTPD FTP Service
    - SSH Daemon
    - MySQL Database Service
    - PHP Versions (5.6, 7.0, 7.1, 7.2, 7.3, 7.4 as well as default)


## Setup

> Clone repo files to **/opt/nginx-manager** path

```bash
mkdir /opt/nginx-manager && git clone https://github.com/mahony0/nginx-manager /opt/nginx-manager
```

> run init file for generating config file and user for login

```bash
php /opt/nginx-manager/init.php --generate="all" --username="USERNAME"
```

> if you want to generate multiple login credentials

```bash
php /opt/nginx-manager/init.php --generate="password" --username="USERNAME2"
```

both commands output password for you to save securely.


## Tasks for v1 Release

- [ ] Test on Ubuntu 20.04
- [ ] Test on Debian 10
- [ ] Test on CentOS 8
- [ ] More convenient url sanitize logic
- [ ] Allow only SSL connection for built-in PHP server


## Screenshots

### Dashboard
![Dashboard](https://uxms.net/storage/app/media/github/1-dash.jpg)

### Main Configurations
![Main Configurations](https://uxms.net/storage/app/media/github/2-configs.jpg)
