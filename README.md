# Filament One

**Filament One** is a powerful and flexible starting point for building robust and scalable admin dashboards. This boilerplate is designed to help developers quickly set up an admin panel with essential features, security measures, and user-friendly management tools.

Built using **FilamentPHP**, it leverages modern PHP development practices to ensure a smooth and efficient experience.

![GitHub stars](https://img.shields.io/github/stars/jeddsaliba/filament-one?style=flat-square)
![GitHub issues](https://img.shields.io/github/forks/jeddsaliba/filament-one?style=flat-square)
![GitHub issues](https://img.shields.io/github/issues/jeddsaliba/filament-one?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)
![PHP Version](https://img.shields.io/badge/PHP-8.2-blue?style=flat-square&logo=php)
![Laravel Version](https://img.shields.io/badge/Laravel-11.0-red?style=flat-square&logo=laravel)
![Filament Version](https://img.shields.io/badge/Filament-3.2-purple?style=flat-square)

**Key Features:**
- **User Management:** Easily manage user accounts, including registration, profile updates, and deletion.
- **Multi-Factor Authentication (MFA):** Enhance security with optional two-factor authentication for user accounts.
- **API Support:** Enable seamless integration with external applications through a robust API layer. Includes authentication, data retrieval, and CRUD operations via RESTful endpoints.
- **API Keys Management:** Enable secure storage and management of API keys and credentials, facilitating seamless integration with internal and third-party APIs.
- **User Roles & Permissions:** Implement role-based access control (RBAC) to ensure users have the right level of access.
- **Messages:** Provides an easy-to-use interface for real-time messaging within Filament admin panels.
- **Export Reports:** Generate and export reports in Excel (.xlsx) or CSV formats for data analysis and record-keeping.
- **Page Builder:** A drag-and-drop interface that allows users to create, customize, and manage pages effortlessly. Supports various content blocks, real-time preview, and responsive design to ensure a seamless experience across devices.

**Why Choose Filament One?**
- **Time-Saving:** Get started quickly with a ready-to-use admin panel instead of building from scratch.
- **Scalability:** Designed to be easily extendable, allowing you to add more features as needed.
- **Security Focused:** Built-in security features like MFA and role-based permissions to protect user data.
- **User-Friendly:** Clean UI and intuitive navigation for a seamless admin experience.
- **Open & Customizable:** Modify and extend the boilerplate according to your project’s requirements.

## Table of Contents
[Getting Started](#getting-started)<br/>
[Setup Local Environment](#environment)<br/>
[Setup with Docker](#docker-setup)<br/>
[Database](#database)<br/>
[Generate Filament Shield Permissions](#generate-filament-shield-permissions)<br/>
[Create Administrator Account](#create-admin-account)<br/>
[Generate Test Data](#generate-test-data)<br/>
[Initialize The Application](#initialize-the-application)<br/>
[API Support](#api-support)<br/>
[Troubleshooting](#troubleshooting)<br/>
[Plugins Used](#plugins-used)<br/>
[Acknowledgments](#acknowledgments)<br/>
[Support](#support)

<a name="getting-started"></a>
## Getting Started
Create a new project using this command:

```bash
composer create-project jeddsaliba/filament-one
```

Install the `dependencies` by running the following commands:

```bash
composer install
npm install
```

<a name="environment"></a>
## Setup Local Environment
Generate a new `.env` file by running:

```bash
cp .env.example .env
```

Configure the `APP_URL` in your `.env` file:

```bash
APP_URL=http://localhost
```

Configure the `MySQL` connection in your `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

or if you're using `PostgreSQL`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Configure the `Mailer` in your `.env` file:
```
MAIL_MAILER=smtp
MAIL_SCHEME=
MAIL_HOST=
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

<a name="docker-setup"></a>
## Setup with Docker

Filament One includes Docker configuration for easy local development. This setup includes PHP-FPM, Nginx, MySQL, Redis, and a queue worker.

### Prerequisites
- [Docker](https://www.docker.com/get-started) and [Docker Compose](https://docs.docker.com/compose/install/) installed

### Quick Start

1. **Clone/Create the project:**
   ```bash
   composer create-project jeddsaliba/filament-one
   cd filament-one
   ```

2. **Create your `.env` file:**
   ```bash
   cp .env.example .env
   ```

3. **Configure Docker-specific settings in `.env`:**
   
   **⚠️ Critical Docker Configuration:**
   ```env
   # Database - MUST use 'mysql' as host (Docker service name)
   DB_CONNECTION=mysql
   DB_HOST=mysql              # ⚠️ Must be 'mysql', not '127.0.0.1'
   DB_PORT=3306
   DB_DATABASE=filament_one   # Match this with docker-compose.yml
   DB_USERNAME=laravel        # Match this with docker-compose.yml
   DB_PASSWORD=password       # Match this with docker-compose.yml

   # Redis - MUST use 'redis' as host (Docker service name)
   REDIS_HOST=redis           # ⚠️ Must be 'redis', not '127.0.0.1'
   REDIS_PORT=6379
   REDIS_PASSWORD=null

   # Queue
   QUEUE_CONNECTION=redis
   ```

   **📋 See [DOCKER_ENV_CHECKLIST.md](DOCKER_ENV_CHECKLIST.md) for complete Docker environment configuration guide.**

4. **Build and start containers:**
   ```bash
   docker-compose build
   docker-compose up -d
   ```

5. **Install Composer dependencies:**
   ```bash
   docker-compose exec app composer install
   ```

6. **Generate application key:**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

7. **Run database migrations:**
   ```bash
   docker-compose exec app php artisan migrate
   ```

8. **Access your application:**
   Open your browser and navigate to: `http://localhost`

### Docker Services

The Docker setup includes the following services:

- **app** - PHP 8.3-FPM application server
- **nginx** - Web server (port 80)
- **mysql** - MySQL 8.0 database (port 3306)
- **redis** - Redis cache and queue (port 6379)
- **queue** - Laravel queue worker

### Useful Docker Commands

**View running containers:**
```bash
docker-compose ps
```

**View logs:**
```bash
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f queue
```

**Execute commands in containers:**
```bash
# PHP/Artisan commands
docker-compose exec app php artisan [command]

# Composer commands
docker-compose exec app composer [command]

# Access container shell
docker-compose exec app sh
```

**Stop containers:**
```bash
docker-compose down
```

**Rebuild containers:**
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Environment Variables for Docker

Your `.env` file must match the Docker service names:
- `DB_HOST=mysql` (not `localhost` or `127.0.0.1`)
- `REDIS_HOST=redis` (not `localhost` or `127.0.0.1`)

Database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) must match the values used in `docker-compose.yml` for the MySQL service.

For detailed Docker environment configuration, see [DOCKER_ENV_CHECKLIST.md](DOCKER_ENV_CHECKLIST.md).

<a name="database"></a>
## Database

### Without Docker
Assuming that you have already created an empty database, run this command to migrate the database tables:

```bash
php artisan migrate
```

### With Docker
If you're using Docker, the database is automatically created. Just run:

```bash
docker-compose exec app php artisan migrate
```

<a name="generate-filament-shield-permissions"></a>
## Generate Filament Shield Permissions

### Without Docker
In order to generate [filament shield](https://filamentphp.com/plugins/bezhansalleh-shield) permissions, run this command:

```bash
php artisan shield:generate --all
```

### With Docker
```bash
docker-compose exec app php artisan shield:generate --all
```

<a name="create-admin-account"></a>
## Create Administrator Account

### Without Docker
In order to create an administrator account, run this command:

```bash
php artisan shield:super-admin
```

### With Docker
```bash
docker-compose exec app php artisan shield:super-admin
```

<a name="generate-test-data"></a>
## Generate Test Data

### Without Docker
You may also run this command in order to populate the database with test data:

```bash
php artisan db:seed
```

### With Docker
```bash
docker-compose exec app php artisan db:seed
```

<a name="initialize-the-application"></a>
## Initialize The Application

### Without Docker

**Option 1: Using Vite (Recommended for development)**
```bash
npm run dev
```

In another terminal, start the PHP server:
```bash
php artisan serve
```

**Option 2: Build assets for production**
```bash
npm run build
php artisan serve
```

### With Docker

**Start all services:**
```bash
docker-compose up -d
```

The application will be available at `http://localhost`. The Docker setup includes:
- Nginx web server
- PHP-FPM application server
- Queue worker (runs automatically)
- MySQL database
- Redis cache

**Note:** For frontend development with hot reload, you may want to run Vite separately on your host machine:
```bash
npm run dev
```

Or use the Docker Node service (if configured) for a fully containerized development environment.

<a name="api-support"></a>
## API Support
Integrate **Filament One** with external applications via APIs.<br/>
[Here](https://github.com/jeddsaliba/filament-one/blob/8b79bf5f4108b9c0f20fac1a2156604259b91a16/docs/postman/Filament%20One%20API.postman_collection.json) is the [postman](https://www.postman.com) collection. Just import it and you're all set!

<a name="troubleshooting"></a>
## Troubleshooting

### CSS and JS not working

**Without Docker:**
```bash
npm run build
```

**With Docker:**
```bash
docker-compose exec app npm run build
```

Or rebuild the assets in the container:
```bash
docker-compose exec app sh -c "npm install && npm run build"
```

### Docker-specific Issues

**1. Connection refused to database:**
- Verify `DB_HOST=mysql` in your `.env` (must be the Docker service name)
- Check if MySQL container is running: `docker-compose ps mysql`
- Ensure database credentials match `docker-compose.yml`

**2. Connection refused to Redis:**
- Verify `REDIS_HOST=redis` in your `.env` (must be the Docker service name)
- Check if Redis container is running: `docker-compose ps redis`

**3. Queue jobs not processing:**
- Ensure `QUEUE_CONNECTION=redis` in your `.env`
- Check queue worker logs: `docker-compose logs -f queue`
- Verify Redis container is running

**4. Permission issues:**
- Fix storage permissions: `docker-compose exec app chmod -R 775 storage bootstrap/cache`
- Fix ownership: `docker-compose exec app chown -R www-data:www-data storage bootstrap/cache`

**5. Container won't start:**
- Rebuild containers: `docker-compose down && docker-compose build --no-cache && docker-compose up -d`
- Check logs: `docker-compose logs [service-name]`

For more Docker troubleshooting, see [DOCKER_ENV_CHECKLIST.md](DOCKER_ENV_CHECKLIST.md).

<a name="plugins-used"></a>
## Plugins Used
These are [Filament Plugins](https://filamentphp.com/plugins) use for this project.

| **Plugin**                                                                                          | **Author**                                              |
| :-------------------------------------------------------------------------------------------------- | :------------------------------------------------------ |
| [Ace Editor](https://github.com/riodwanto/filament-ace-editor)                                      | [Rio Dewanto P](https://github.com/riodwanto)           |
| [ActivityLog](https://github.com/rmsramos/activitylog)                                              | [Rômulo Ramos](https://github.com/rmsramos)             |
| [API Service](https://github.com/rupadana/filament-api-service)                                     | [Rupadana](https://github.com/rupadana)                 |
| [Breezy](https://github.com/jeffgreco13/filament-breezy)                                            | [Jeff Greco](https://github.com/jeffgreco13)            |
| [Comments](https://github.com/parallax/filament-comments)                                           | [Parallax](https://github.com/parallax)                 |
| [Date Range Filter and Picker](https://github.com/malzariey/filament-daterangepicker-filter)        | [Majid Al Zariey](https://github.com/malzariey)         |
| [Easy Footer](https://github.com/devonab/filament-easy-footer)                                      | [Alexandre](https://github.com/Devonab)                 |
| [Environment Indicator](https://github.com/pxlrbt/filament-environment-indicator)                   | [Dennis Koch](https://github.com/pxlrbt)                |
| [Filament Spatie Media Library](https://github.com/filamentphp/spatie-laravel-media-library-plugin) | [Filament Official](https://github.com/filamentphp)     |
| [Filament Spatie Settings](https://github.com/filamentphp/spatie-laravel-settings-plugin)           | [Filament Official](https://github.com/filamentphp)     |
| [Global Search Modal](https://github.com/CharrafiMed/global-search-modal)                           | [Mohamed Charrafi](https://github.com/CharrafiMed)      |
| [Grapes JS](https://github.com/dotswan/filament-grapesjs-v3)                                        | [dotSwan](https://github.com/dotswan)                   |
| [Impersonate](https://github.com/stechstudio/filament-impersonate)                                  | [Signature Tech Studio](https://github.com/stechstudio) |
| [Phone Input](https://github.com/ysfkaya/filament-phone-input)                                      | [Yusuf Kaya](https://github.com/ysfkaya)                |
| [Shield](https://github.com/bezhanSalleh/filament-shield)                                           | [Bezhan Salleh](https://github.com/bezhansalleh)        |
| [Spatie Laravel Health](https://github.com/shuvroroy/filament-spatie-laravel-health)                | [Shuvro Roy](https://github.com/shuvroroy)              |

<a name="acknowledgments"></a>
## Acknowledgments
- [FilamentPHP](https://filamentphp.com)
- [Laravel](https://laravel.com)

<a name="support"></a>
## Support
- [Report a bug](https://github.com/jeddsaliba/filament-one/issues)
- [Request a feature](https://github.com/jeddsaliba/filament-one/issues)
- [Email support](mailto:jeddsaliba@gmail.com)

## Show Your Support

Give a ⭐️ if this project helped you!
