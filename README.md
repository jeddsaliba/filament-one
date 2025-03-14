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
[Database](#database)<br/>
[Generate Filament Shield Permissions](#generate-filament-shield-permissions)<br/>
[Create Administrator Account](#create-admin-account)<br/>
[Generate Test Data](#generate-test-data)<br/>
[Initialize The Application](#initialize-the-application)<br/>
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

<a name="database"></a>
## Database
Assuming that you have already created an empty database, run this command to migrate the database tables:

```bash
php artisan migrate
```

<a name="generate-filament-shield-permissions"></a>
## Generate Filament Shield Permissions
In order to generate [filament shield](https://filamentphp.com/plugins/bezhansalleh-shield) permissions, run this command:

```bash
php artisan shield:generate --all
```

<a name="create-admin-account"></a>
## Create Administrator Account
In order to create an administrator account, run this command:

```bash
php artisan shield:super-admin
```

<a name="generate-test-data"></a>
## Generate Test Data
You may also run this command in order to populate the database with test data:

```bash
php artisan db:seed
```

<a name="initialize-the-application"></a>
## Initialize The Application
In order to start the application, use any of the following commands:

```bash
npm run dev
```

or

```bash
php artisan serve
```

<a name="troubleshooting"></a>
## Troubleshooting
1. If the css and js for the custom page are not working, please run this command:

```bash
npm run build
```

<a name="plugins-used"></a>
## Plugins Used
These are [Filament Plugins](https://filamentphp.com/plugins) use for this project.

| **Plugin**                                                                                          | **Author**                                              |
| :-------------------------------------------------------------------------------------------------- | :------------------------------------------------------ |
| [Ace Editor](https://github.com/riodwanto/filament-ace-editor)                                      | [Rio Dewanto P](https://github.com/riodwanto)           |
| [ActivityLog](https://github.com/rmsramos/activitylog)                                              | [Rômulo Ramos](https://github.com/rmsramos)             |
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
