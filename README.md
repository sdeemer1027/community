# Community — Neighbor, Friends & Family Network

A web-based community platform designed to help people discover and connect with registered members in their local area, as well as friends and family who live farther away.

The platform is being developed with Laravel and is designed around user choice, privacy, and member-approved connections.

## Project Status

**Development stage:** Early development

The application currently includes user registration, authentication, member search, profile management, connection requests, and accepted connections.

Features will continue to be developed and refined.

## Features

### User Accounts
- Member registration and login
- Authentication-protected pages
- Profile management
- Email verification support
- Password reset functionality

### Member Directory
- Browse registered members
- Search by member name or ZIP code
- Display member names and ZIP codes
- Pagination for member listings

### Connection Requests
- Send connection requests to other members
- Receive incoming connection requests
- Accept or decline requests
- Prevent requests to yourself
- Prevent duplicate connection requests in either direction
- View accepted connections

### Navigation
- Responsive desktop and mobile navigation
- Dashboard
- Find Members
- Connection Requests
- My Connections
- Profile and logout links

### Planned Development
- Search for nearby members within a 20-mile radius
- Location-based search using ZIP code geographic coordinates
- Improved privacy and member connection preferences
- Friend and family connections regardless of geographic distance
- Additional community features as requirements evolve

**Note:** Geographic distance calculations are not implemented yet. The current directory searches member names and ZIP code text; it does not yet find members within a 20-mile radius.

## Technology Stack

- **Backend:** PHP 8.3 and Laravel 13
- **Database:** MySQL 8.0
- **Frontend:** Blade templates, Tailwind CSS, Alpine.js, and Vite
- **Authentication:** Laravel Breeze
- **Dependency management:** Composer and npm
- **Version control:** Git and GitHub

## System Requirements

The following requirements apply to the current development setup. Production deployments may require additional configuration.

- PHP 8.3 or a compatible supported PHP version
- Composer 2.x
- MySQL 8.0 or a compatible supported MySQL version
- Node.js and npm
- Git
- A web browser
- PHP extensions required by Laravel and its installed dependencies

Required PHP extensions commonly include `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `pdo_mysql`, `session`, `tokenizer`, and `xml`.

## Installation

### 1. Clone the Repository

Clone the repository from GitHub and enter the project directory:

```bash
git clone <YOUR_GITHUB_REPOSITORY_URL>
cd community
```

Replace the placeholder with the actual repository URL.

If you already have the project locally, skip this step.

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment Variables

Create a local environment file:

```bash
copy .env.example .env
```

On Windows Command Prompt, use the command above. If `.env` already exists, do not overwrite it.

Generate an application key if one has not already been generated:

```bash
php artisan key:generate
```

Configure the database settings in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=community
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Replace the database username and password with your own local database credentials.

Create the `community` database in MySQL before running migrations. Use a dedicated database for this application.

### 4. Install Frontend Dependencies

```bash
npm install
```

### 5. Build Frontend Assets

For a production-style asset build:

```bash
npm run build
```

For local frontend development with automatic asset updates, use:

```bash
npm run dev
```

Keep the development process running in its own terminal while working on the frontend.

### 6. Run Database Migrations

```bash
php artisan migrate
```

This creates the application's required database tables.

**Important:** Back up any existing data before applying database changes. Do not use destructive commands such as `php artisan migrate:fresh` on a database containing data you need to preserve.

### 7. Start the Application

Run:

```bash
php artisan serve
```

Open the local address displayed by Laravel, typically:

`http://127.0.0.1:8000`

Register an account and sign in to begin testing the application.

## Main Application Routes

| Route | Purpose |
|---|---|
| `/` | Welcome page |
| `/dashboard` | Member dashboard |
| `/profile` | Profile management |
| `/members` | Browse and search members |
| `/connections` | Review incoming connection requests |
| `/my-connections` | View accepted connections |

Application pages requiring authentication must be accessed by signing in.

## Database Overview

The application currently uses these primary tables:

- `users` — member accounts and profile information, including ZIP code
- `connections` — connection requests and their statuses
- `migrations` — Laravel's migration history
- `cache` and `jobs` — framework support tables

Connection records contain a requester, recipient, status, and timestamps. Supported statuses currently include `pending`, `accepted`, and `declined`.

## Security and Privacy

- Keep `.env` private and never commit real passwords, application keys, or API credentials.
- Use `.env.example` to document environment variable names without exposing secrets.
- Do not publish production database credentials.
- Keep dependencies updated and review security advisories.
- Validate user input and enforce authorization on connection actions.
- Configure HTTPS, secure cookies, backups, logging, and appropriate server permissions before production deployment.

Member visibility and connection preferences will be expanded as the project develops.

## Development Guidelines

- Use database migrations to manage schema changes.
- Test changes locally before deploying.
- Commit work to Git regularly.
- Back up important data before database changes.
- Document new configuration requirements and features.

## License

No project-specific license has been established yet. Unless a license is added to this repository, do not assume that the project is open source or that others have permission to redistribute or reuse its code.

---

**Community** — Building connections between neighbors, friends, and family.