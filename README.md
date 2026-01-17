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
[Setup Local Environment](#local-setup)<br/>
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

<a name="local-setup"></a>
## Setup Local Environment

This section covers setting up Filament One for local development without Docker.

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0+ or PostgreSQL 13+
- Redis (optional, for caching and queues)

### Step 1: Create Project and Environment File

```bash
# Create project
composer create-project jeddsaliba/filament-one
cd filament-one

# Create environment file
cp .env.example .env
```

### Step 2: Generate Application Key

```bash
php artisan key:generate
```

### Step 3: Configure Application Settings

Update the following in your `.env` file:

**Application URL:**
```env
APP_NAME="Filament One"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC
```

### Step 4: Configure Database

**For MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**For PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**Important:** Make sure you've created the database before running migrations.

### Step 5: Configure Redis (Optional but Recommended)

If you have Redis installed locally:

```env
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
REDIS_DB=0
REDIS_CACHE_DB=1

# Use Redis for cache and sessions
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Step 6: Configure Mailer

Update mail settings in your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Note:** For local development, you can use [Mailpit](https://github.com/axllent/mailpit) or [MailHog](https://github.com/mailhog/MailHog) for testing emails.

### Step 7: Build Frontend Assets

Install npm dependencies and build assets:

```bash
npm install
npm run build
```

Or for development with hot reload:

```bash
npm run dev
```

### Next Steps

After completing the setup above, proceed to:
- [Database Migrations](#database)
- [Generate Filament Shield Permissions](#generate-filament-shield-permissions)
- [Create Administrator Account](#create-admin-account)
- [Generate Test Data](#generate-test-data)

<a name="docker-setup"></a>
## Setup with Docker

Filament One includes Docker configuration for easy local development. This setup includes PHP-FPM, Nginx, MySQL, Redis, and a queue worker - everything you need to get started quickly.

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed (includes Docker and Docker Compose)
- At least 4GB of available RAM
- Ports 80, 3306, and 6379 available

### Docker Services Included

The Docker setup provides the following services:

| Service | Description | Port |
|---------|-------------|------|
| **app** | PHP 8.3-FPM application server | Internal |
| **nginx** | Nginx web server | 80 |
| **mysql** | MySQL 8.0 database | 3306 |
| **redis** | Redis cache and queue | 6379 |
| **queue** | Laravel queue worker | Internal |

### Step 1: Create Project and Environment File

```bash
# Create project
composer create-project jeddsaliba/filament-one
cd filament-one

# Create environment file
cp .env.example .env
```

### Step 2: Configure Docker-Specific Environment Variables

**⚠️ Critical:** Docker services communicate using service names as hostnames. Your `.env` file must use these service names.

Update your `.env` file with the following Docker-specific configuration:

```env
# Application Settings
APP_NAME="Filament One"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC

# Database Configuration
# ⚠️ DB_HOST must be 'mysql' (Docker service name, not localhost!)
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Redis Configuration
# ⚠️ REDIS_HOST must be 'redis' (Docker service name, not localhost!)
REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=null
REDIS_DB=0
REDIS_CACHE_DB=1

# Cache & Session (using Redis)
CACHE_STORE=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

# Queue Configuration
QUEUE_CONNECTION=redis

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Important Notes:**
- `DB_HOST=mysql` - Must use the Docker service name, not `127.0.0.1` or `localhost`
- `REDIS_HOST=redis` - Must use the Docker service name, not `127.0.0.1` or `localhost`
- Database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) are used by both Laravel and the MySQL container initialization

### Step 3: Build and Start Docker Containers

```bash
# Build the containers
docker-compose build

# Start all services in detached mode
docker-compose up -d
```

All services should show as "Up" or "running".

### Step 4: Generate Application Key

```bash
docker-compose exec app php artisan key:generate
```

### Next Steps

After completing the Docker setup:
- [Database Migrations](#database)
- [Generate Filament Shield Permissions](#generate-filament-shield-permissions)
- [Create Administrator Account](#create-admin-account)
- [Generate Test Data](#generate-test-data)

### Troubleshooting Docker Setup

If you encounter issues, see the [Troubleshooting](#troubleshooting) section below.

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
