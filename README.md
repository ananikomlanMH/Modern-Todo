<p align="center">
  <img src="public/assets/images/logo.svg" alt="Todo App Logo" width="200">
</p>

# Modern Todo Application

## About

A modern, feature-rich task management application built with Laravel and React. Organize your tasks with categories, tags, subtasks, and track your productivity efficiently.

## Features

- **Task Management**
  - Create, update, and delete tasks
  - Set priority levels (low, medium, high, urgent)
  - Track task status (pending, in progress, completed, cancelled)
  - Set due dates and completion tracking
  - Bulk actions for multiple tasks
  - Task filtering and sorting

- **Organization**
  - Categorize tasks with custom categories
  - Tag system for flexible organization
  - Create subtasks for complex tasks
  - Task dependencies tracking
  - Project grouping

- **User Experience**
  - Real-time updates with WebSocket
  - Responsive design for all devices
  - Dark/Light theme support
  - Drag and drop interface
  - Keyboard shortcuts

- **Team Collaboration**
  - Task sharing and assignment
  - Team workspaces
  - Activity timeline
  - Comments and discussions
  - Role-based permissions

- **Productivity Features**
  - Calendar integration
  - Progress tracking
  - Performance analytics
  - Time tracking
  - Export/Import capabilities

## Technologies

### Backend
- PHP 8.2
- Laravel 10
- MySQL 8.0
- Redis for caching
- Laravel Echo for real-time
- Laravel Sanctum for authentication
- Pest PHP for testing

### Frontend
- React 18
- TypeScript
- Tailwind CSS
- Shadcn UI
- Inertia.js
- Vite

### DevOps
- Docker
- GitHub Actions for CI/CD
- PHPStan for static analysis
- Nginx
- Docker Hub

## Installation

### Using Docker (Recommended)

1. Clone the repository:
```bash
git clone https://github.com/yourusername/todo-app
cd todo-app
```

2. Copy environment files:
```bash
cp .env.example .env
```

3. Start Docker containers:
```bash
docker-compose up -d
```

4. Install dependencies and setup application:
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app npm install
docker-compose exec app npm run build
```

5. Access the application:
```bash
docker-compose exec app php artisan serve
```

### Manual Installation

1. Prerequisites:
```bash
PHP >= 8.2
Node.js >= 20
MySQL >= 8.0
Composer
npm
```

2. Clone and setup:
```bash
# Clone repository
git clone https://github.com/yourusername/todo-app
cd todo-app

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate

# Build assets
npm run build

# Start development server
php artisan serve
```

## Development

### Start development servers:
```bash
# Terminal 1 - Laravel
php artisan serve

# Terminal 2 - Vite
npm run dev
```

### Running tests:
```bash
# Feature and Unit tests
php artisan test

# With coverage
php artisan test --coverage
```

### Code Analysis:
```bash
# Static analysis
./vendor/bin/phpstan analyse

# Code style
./vendor/bin/pint
```

## Docker Commands

### Basic usage:
```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f

# Execute commands
docker-compose exec app php artisan [command]
```

### Database:
```bash
# Access MySQL
docker-compose exec db mysql -u root -p

# Backup database
docker-compose exec db mysqldump -u root -p todo > backup.sql
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, please open an issue in the GitHub repository or contact the maintainers.
