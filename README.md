# WEM - Fantasy Manager

A fantasy football management game built with Laravel and React.

## Local Development Setup with Laravel Herd

This project uses [Laravel Herd](https://herd.laravel.com/) for local development. Herd provides a blazing fast, native Laravel and PHP development environment for macOS and Windows.

### Prerequisites

- **macOS**: macOS 12.0 or higher
- **Windows**: Windows 10 or higher
- [Laravel Herd](https://herd.laravel.com/) installed and running
  - [macOS Installation](https://herd.laravel.com/docs/macos/getting-started/installation)
  - [Windows Installation](https://herd.laravel.com/docs/windows/getting-started/installation)
- [Composer](https://getcomposer.org/) (included with Herd)
- [Node.js](https://nodejs.org/) (included with Herd)

### Quick Start

1. **Clone the repository**

   ```bash
   git clone <repository-url>
   cd fantasy-manager
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Install Node.js dependencies**

   ```bash
   pnpm install
   ```

4. **Set up environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**

   The project supports multiple database options:

   - **SQLite** (recommended for development):

     ```bash
     touch database/database.sqlite
     ```

     Update `.env`:

     ```
     DB_CONNECTION=sqlite
     ```

   - **MySQL/PostgreSQL** (if using Herd Pro services):
     Update `.env` with your database credentials from Herd's service management.

6. **Run migrations and seeders**

   ```bash
   php artisan migrate --seed
   ```

7. **Build frontend assets**

   ```bash
   pnpm run dev
   ```

8. **Start the development server**

   ```bash
   php artisan serve
   ```

   Or use Herd's automatic site detection by placing your project in a directory that Herd monitors.

### Development Workflow

- **Frontend development**: Run `pnpm run dev` for hot reloading
- **Backend development**: Herd automatically handles PHP and Laravel
- **Database**: Use Herd's built-in database services or SQLite for development
- **Testing**: Run `php artisan test` for PHP tests, `pnpm test` for frontend tests

### Available Commands

```bash
# Laravel commands
php artisan serve          # Start development server
php artisan migrate        # Run migrations
php artisan migrate:fresh  # Reset database and run migrations
php artisan db:seed        # Run seeders
php artisan test           # Run PHP tests
php artisan tinker         # Laravel REPL

# Frontend commands
pnpm run dev              # Start Vite dev server
pnpm run build            # Build for production
pnpm run test             # Run frontend tests
pnpm run lint             # Lint code
```

### Project Structure

- `app/` - Laravel application logic
- `resources/js/` - React components and pages
- `database/` - Migrations and seeders
- `routes/` - Application routes
- `tests/` - Test files

### Troubleshooting

- **Herd not detecting site**: Ensure your project is in a directory that Herd monitors
- **Database connection issues**: Check your `.env` configuration and ensure the database service is running
- **Asset compilation errors**: Run `pnpm install` and ensure Node.js version is compatible

#### Windows Performance Optimization

If you're experiencing slow performance on Windows, Windows Defender might be scanning Herd folders. To improve performance:

1. Open **Windows Security**
2. Go to **"Virus & threat protection"** → **"Manage settings"**
3. Scroll to **"Exclusions"** → **"Add or remove exclusions"**
4. Add folder exclusion: `%USERPROFILE%\.config\herd`

This typically resolves to `C:\Users\YourUsername\.config\herd` and will prevent Windows Defender from scanning Herd's configuration folder.

### Production Deployment

For production deployment, refer to Laravel's official deployment documentation and ensure all environment variables are properly configured.
