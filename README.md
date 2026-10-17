
# Community — Neighbor, Friends & Family Network

A self-hosted community platform designed to help people discover and connect with registered members in their local area, as well as friends and family who live farther away.

The platform is being developed with Laravel and is designed around user choice, privacy, member-approved connections, and community participation.

The long-term goal is to provide an installable, configurable community platform that customers can run on their own domain and hosting account, with customizable branding, roles, permissions, and features.

## Project Status

**Development stage:** Active development

The application has progressed beyond its initial account and connection functionality. Current development includes a community dashboard, member directory, connection management, community posts, image and video attachments, a media library, and an administration system.

Administrative capabilities now include role-based permissions, post moderation, and a reporting workflow that allows members to report posts for moderator review.

The platform is still under development. Installation, configuration, security review, testing, and production-readiness work remain ongoing.

## Features

### User Accounts

- Member registration and login
- Authentication-protected pages
- Profile management
- Email verification support
- Password reset functionality
- ZIP code and profile information
- Account roles and permission assignments

### Member Directory

- Browse registered members
- Search by member name or ZIP code
- Display member names and ZIP codes
- Pagination for member listings
- Member profile pages

**Note:** Geographic distance calculations are not yet implemented. The current directory searches member names and ZIP code text; it does not yet locate members within a 20-mile radius.

### Connection Requests

- Send connection requests to other members
- Receive incoming connection requests
- Accept or decline requests
- Prevent requests to yourself
- Prevent duplicate connection requests in either direction
- View accepted connections
- Use accepted connections to support community interaction and audience restrictions

### Community Dashboard

- Responsive community dashboard
- Quick links and community navigation
- Create a Post area
- Community feed
- Feed refreshes automatically every 45 seconds
- Visual highlighting of posts created by accepted connections
- Post timestamps and author information
- Responsive layout for different screen sizes

### Community Posts

- Create community posts
- Configure supported post audiences, including public, friends, family, and private/self-only options where supported by the current account rules
- Display post text in the community feed
- Attach images and videos to posts
- Display image previews and video playback controls
- Restrict access to posts and attachments according to applicable audience and permission rules
- Hide and restore posts through moderation rather than permanently deleting them

Post creation and audience restrictions are still evolving. Review the current controllers and authorization rules before deploying to a public production environment.

### Media Library

- Store member-uploaded media using the application's storage system
- Organize media for use in community features
- Attach images and videos to posts
- Display media in the community feed
- Serve attachments through an authenticated application route
- Keep uploaded files in private storage rather than exposing their storage paths directly

Additional media library and gallery capabilities may be added as the platform develops.

### Administration Panel

The administration area provides a dedicated dashboard and navigation system.

Current administration features include:

- Admin dashboard with community overview information
- Total member count
- Super administrator count
- Free member count
- Member administration pages
- Role assignment
- Roles and Permissions page
- Reported Posts review page
- Shared administration sidebar
- Permission-protected administration routes

The Admin Panel is designed to grow as additional community management features are implemented.

### Roles and Permissions

The platform includes a role-based permission foundation.

Current role categories include:

- **Super Administrator** — full administrative access
- **Administrator** — assigned member-management and administrative permissions
- **Moderator** — assigned content moderation and report-review permissions
- **Member** — standard community participation permissions

Permission checks are enforced through application middleware and the User model's permission-checking method.

Implemented permission categories include:

- Member viewing and management
- Membership management
- Role and permission management
- System settings
- Integrations
- Post moderation
- Comment moderation
- Report review
- Post creation
- Comment creation
- Report submission

Actual access depends on the permissions assigned to each role. The permissions and role assignments should be reviewed and tested before production deployment.

### Post Moderation

Authorized moderators can manage reported or inappropriate content.

Current moderation capabilities include:

- View posts in the moderation area
- Hide posts
- Restore hidden posts
- Keep hidden posts out of the normal community feed
- Restrict access to hidden post attachments for ordinary members
- Preserve posts instead of permanently deleting them through the hide/restore workflow

Moderation actions are protected by permission checks.

### Reported Posts and Review Workflow

Members can report posts created by other members.

The reporting workflow currently supports:

- Submit a report from a community post
- Select a reason for reporting
- Provide optional additional details
- Prevent duplicate reports by the same member for the same post
- Store reports for moderator review
- Display report status as Pending Review or Reviewed
- Show the reporting member and report submission time
- Display the reported post's text and author
- Display attached images and videos for authorized moderators
- Show whether the original post is visible or hidden
- Mark a report as reviewed
- Record the reviewing administrator or moderator and the review date/time
- Access the review page through the Admin sidebar

Available report reasons currently include:

- Spam or unwanted advertising
- Harassment or bullying
- Inappropriate content
- False or misleading information
- Other concern

**Important:** Submitting a report does not automatically hide or delete the reported post. A moderator reviews the report and takes any necessary moderation action separately.

### Navigation

Current application navigation includes:

- Dashboard
- Find Members
- Connection Requests
- My Connections
- Profile and logout links
- Admin Panel
- Members administration
- Reported Posts
- Roles and Permissions
- Community Dashboard return link

The navigation is designed to support both ordinary members and authorized administrators.

## Planned Development

Potential future features include:

- Search for nearby members within a 20-mile radius
- ZIP code geographic coordinate lookup and distance calculations
- Improved member visibility and connection preferences
- Expanded friend and family connection features
- Member-to-member chat
- Video chat
- Local community forums
- Local events
- Lost-and-found posts
- Neighbor assistance and community help requests
- Additional moderation tools and report-management improvements
- Commenting and expanded interaction features
- Configurable feature modules
- External service and API integrations
- Customizable installation branding and appearance
- Improved installation and configuration workflow
- Additional automated tests and production hardening

These features are planned possibilities and should not be considered implemented unless reflected in the current source code.

## Technology Stack

The current development environment includes:

- **Backend:** PHP 8.3 and Laravel 13
- **Database:** MySQL 8.0
- **Frontend:** Blade templates, Bootstrap-based responsive components, Alpine.js where used, and Vite
- **Authentication:** Laravel authentication scaffolding and middleware
- **Dependency management:** Composer and npm
- **Media storage:** Laravel filesystem and private local storage
- **Icons:** Font Awesome
- **Version control:** Git and GitHub

Exact dependency versions are recorded in `composer.lock` and `package-lock.json`. Consult those files when reproducing the development environment.

## System Requirements

The following requirements apply to the current development setup. Production deployments may require additional configuration.

- PHP 8.3 or a compatible version supported by the installed Laravel dependencies
- Composer 2.x
- MySQL 8.0 or a compatible supported database
- Node.js and npm
- Git
- A web browser
- A web server such as Apache or Nginx, or Laravel's development server for local testing
- PHP extensions required by Laravel and installed dependencies

Commonly required PHP extensions include:

`ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `pdo_mysql`, `session`, `tokenizer`, and `xml`.

The project has been developed locally on Windows using WAMP. Production server requirements depend on the hosting provider and deployment configuration.

## Installation

These instructions describe a typical local Laravel installation. Run commands from the project root.

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

Create a local environment file if one does not already exist.

**Windows Command Prompt:**

```cmd
copy .env.example .env
```

If `.env` already exists, do not overwrite it.

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

Create the `community` database in MySQL before running migrations. Use a dedicated database for the application.

### 4. Install Frontend Dependencies

```bash
npm install
```

### 5. Build Frontend Assets

For a production-style asset build:

```bash
npm run build
```

For local frontend development with automatic asset updates:

```bash
npm run dev
```

Keep the development process running in its own terminal while working on the frontend.

### 6. Run Database Migrations and Seeders

To create the required database tables:

```bash
php artisan migrate
```

For a new development database, the project's seeders can also be run with:

```bash
php artisan db:seed
```

Alternatively, use `php artisan migrate --seed` for a new database when appropriate.

**Important:** Review the project's seeders before running them against an existing database. Back up important data before applying schema changes. Do not use destructive commands such as `php artisan migrate:fresh` on a database containing data you need to preserve.

### 7. Start the Application

Run:

```bash
php artisan serve
```

Open the local address displayed by Laravel, typically:

`http://127.0.0.1:8000`

Register an account and sign in to begin testing the application.

Administrative features require an account with the appropriate role and permissions. Use the project's supported seeding or role-assignment process to configure development administrator accounts.

## Main Application Routes

The following table summarizes important application routes. Some routes require authentication, email verification, or specific permissions.

| Route | Purpose |
|---|---|
| `/` | Welcome page |
| `/dashboard` | Member dashboard |
| `/profile` | Profile management |
| `/members` | Browse and search members |
| `/connections` | Review incoming connection requests |
| `/my-connections` | View accepted connections |
| `/admin` | Administration dashboard, subject to the configured route |
| `/admin/post-reports` | Review reported posts, requiring report-review permission |
| `/moderation` | Review and manage moderated posts, requiring moderation permission |

Check `routes/web.php` and use `php artisan route:list` for the authoritative list of registered routes.

## Database Overview

The application uses Laravel migrations to manage its database schema.

Primary application tables include:

- `users` — member accounts and profile information, including ZIP code and role-related fields
- `connections` — connection requests, statuses, and related connection preferences
- `posts` — community posts, audience settings, and moderation state
- Media-related tables — media records and relationships between uploaded media and posts
- `post_reports` — submitted post reports, reporting member, reason, optional details, review status, reviewer, and review timestamp
- Roles and permissions tables — role definitions, permission definitions, and their assignments
- `migrations` — Laravel migration history
- `cache` and `jobs` — framework support tables where configured

The exact schema is defined by the migration files in `database/migrations`.

Connection records support statuses including `pending`, `accepted`, and `declined`.

The `post_reports` table prevents duplicate reports for the same post by the same reporting member. Reports use a status such as `pending` or `reviewed`.

## Useful Development Commands

Run commands from the project root:

```bash
# List registered routes
php artisan route:list

# Inspect the report review routes
php artisan route:list --name=admin.post-reports -v

# Clear cached application files
php artisan optimize:clear

# Compile Blade views to check for template errors
php artisan view:cache

# Clear compiled Blade views
php artisan view:clear

# Build frontend assets
npm run build

# Start the Laravel development server
php artisan serve
```

When changing routes, middleware, permissions, or views, test the affected pages using both authorized and unauthorized accounts.

## Security and Privacy

Security and privacy are important design goals. Current protections include:

- Authentication-protected routes
- Email-verification middleware on applicable routes
- Permission middleware for protected administration actions
- Input validation for report submissions
- Prevention of duplicate reports for the same post by the same member
- Escaped Blade output for user-provided text
- Private storage for uploaded post attachments
- Authenticated media delivery with audience and permission checks
- Moderator-only access to hidden post attachments
- Separate report review and post hide/restore actions

Before public production deployment, review and test at least the following:

- Authorization for every route and action, including direct URL access
- Post audience rules and media access for authors, connections, family members, moderators, and unrelated members
- Upload validation, file-size limits, accepted MIME types, and storage permissions
- CSRF protection, input validation, output escaping, rate limits, and abuse prevention
- Password reset, email verification, session and cookie security
- Error handling, logging, backups, database migrations, and recovery procedures
- HTTPS, production environment variables, `APP_DEBUG=false`, and least-privilege database credentials
- Administrative account creation and protection
- Privacy and data-retention expectations for member reports and uploaded content

Never commit `.env`, API keys, passwords, private user uploads, or other secrets to GitHub.

The project is still under development; these protections should not be interpreted as a complete security audit or a guarantee of production readiness.

## Development Guidelines

- Use database migrations to manage schema changes.
- Preserve existing functionality when adding new features.
- Avoid reinstalling scaffolding or replacing working layouts unless explicitly required.
- Test changes locally before deploying.
- Commit work to Git regularly.
- Back up important data before database changes.
- Enforce authorization on new routes and actions.
- Test features with different roles and permission levels.
- Document new configuration requirements and implemented features.
- Update this README as the application evolves.

## License

No project-specific license has been established yet. Unless a license is added to this repository, do not assume that the project is open source or that others have permission to redistribute or reuse its code.

---

**Community** — Building connections between neighbors, friends, and family.
