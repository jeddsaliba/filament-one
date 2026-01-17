# Changelog

All notable changes to this project will be documented in this file.

## [1.5.0] - 2026-01-27
### Added
- **Docker Support:** Full Docker containerization for easy local development and deployment.
  - Complete Docker Compose setup with PHP 8.3-FPM, Nginx, MySQL 8.0, Redis, and Laravel queue worker
  - Dockerfile with optimized PHP extensions and Composer installation
  - Automated container orchestration for all required services
  - Comprehensive Docker documentation and setup guides in README
  - Environment configuration guide for Docker-specific settings
  - Quick start commands for building and running the entire stack

---

## [1.4.1] - 2025-08-12
### Added
- **Message Notifications:** When sending a message to a person or group, they will receive a notification badge and ability to redirect to the conversation if the notification is clicked.

---

## [1.4.0] - 2025-03-17
### Added
- **API Support:** Introduced a new API layer that enables seamless integration with external applications. This includes authentication, data retrieval, and CRUD operations via RESTful endpoints.

---

## [1.3.5] - 2025-03-16
### Fixed
- **Messages:** Redirect to conversation after creating new message.

---

## [1.3.4] - 2025-03-16
### Removed
- Automatic migration after creating the project.

### Updated
- **2FA:** Enable/disable 2FA via .env.

---

## [1.3.3] - 2025-03-14
### Added
- **Inbox:** Factory and seeder.
- **Message:** Factory and seeder.

---

## [1.3.2] - 2025-03-08
### Updated
- **Messages:** Message resources.

---

## [1.3.1] - 2025-03-08
### Fixed
- **Messages:** Message attachments conversion.

---

## [1.3.0] - 2025-03-08
### Added
- **Messages:** Provides an easy-to-use interface for real-time messaging within Filament admin panels.
  - User-to-User & Group Chats
  - Unread Message Badges
  - File Attachments
  - Configurable Refresh Interval
  - Timezone Support

---

## [1.2.0] - 2025-03-05
### Added
- **Easy Footer:** A simple plugin to display a customizable footer in your Filament application.
- **Date Range Filter and Picker:** This package uses the daterangepicker library to filter a date within a range.

---

## [1.1.2] - 2025-03-03
### Changed
- Updated dependencies.

---

## [1.1.1] - 2025-03-03
### Fixed
- **User Onboarding Email:** Send user credentials via email upon account creation.

---

## [1.1.0] - 2025-03-03
### Added
- **Page Builder:** Introduced a drag-and-drop Page Builder, allowing users to create and customize pages effortlessly.
  - Supports various content blocks, including text, images, videos, and forms.
  - Live preview mode for real-time editing and layout adjustments.
  - Responsive design support to ensure pages look great on all devices.
  - Integration with Tailwind CSS support.
- **User Onboarding Email:** Ability to send user credentials via email upon account creation.

### Removed
- Forced 2FA setup on initial log in. Users may set up their 2FA on **My Profile**.

---

## [1.0.0] - 2025-02-28
### Added
- Initial release of **Filament One**
- Features include:
  - User account creation and management
  - Roles and permissions management
  - Basic authentication system
  - API keys and credentials management
  - Activity logs
  - Application health checker