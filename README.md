# Task App — Multi-Tenant Task Management API

A RESTful API built with Laravel 12 for managing tasks across isolated tenants. Each tenant's data is fully scoped using a global query scope — no tenant can access another's tasks.

## Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Language | PHP 8.3+ |
| Auth | Laravel Sanctum |
| Database | MySQL |
| Cache | Redis |
| Queue | Redis |

## Architecture

- **Thin controllers** — no business logic, only dispatch to services and return resources
- **Service layer** — `AuthService`, `TaskService` own all business logic
- **Form Requests** — all validation isolated from controllers
- **API Resources** — all responses shaped through resource classes
- **PHP Enums** — `TaskStatus` enum (`todo`, `in_progress`, `done`)
- **Global Scope** — every task query automatically filters by the authenticated user's `tenant_id`

## Requirements

- PHP 8.3+
- Composer
- MySQL
- Redis

## Setup

```bash
git clone <repo-url>
cd task-app

composer install

cp .env.example .env
php artisan key:generate
```

Configure `.env`:

```env
DB_DATABASE=task_app
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

Run migrations and seed:

```bash
php artisan migrate --seed
php artisan queue:work
```

## API Endpoints

### Auth (public)

| Method | URI | Description |
|---|---|---|
| POST | `/api/register` | Register a new user (creates tenant) |
| POST | `/api/login` | Login, returns Sanctum token |

### Auth (protected)

| Method | URI | Description |
|---|---|---|
| POST | `/api/logout` | Revoke current token |

### Tasks (protected — tenant-scoped)

| Method | URI | Description |
|---|---|---|
| GET | `/api/tasks` | List tasks (paginated, filterable) |
| POST | `/api/tasks` | Create a task |
| GET | `/api/tasks/{id}` | Show a task |
| PUT/PATCH | `/api/tasks/{id}` | Update a task |
| DELETE | `/api/tasks/{id}` | Delete a task |

### Query Parameters (GET /api/tasks)

| Parameter | Type | Description |
|---|---|---|
| `status` | string | Filter by `todo`, `in_progress`, or `done` |
| `search` | string | Search by task title |
| `per_page` | integer | Results per page (default 15) |

## Assumptions

For simplicity, the registration endpoint accepts a `tenant_id` to associate a user with an existing tenant. Tenant creation is outside the scope of this assignment.

### Real-World HR Systems

In a production HR system, users typically do not self-register or provide a `tenant_id`.

A company administrator (HR Admin) first creates the organization (tenant) and then creates or invites employees. Each employee is automatically assigned to the administrator's tenant, and the user only activates their account by setting a password or logging in. This prevents users from joining arbitrary organizations and keeps tenant management centralized.
## Multi-Tenancy

Tenancy is enforced via a Laravel **Global Scope** on the `Task` model. Every query automatically appends `WHERE tenant_id = ?` using the authenticated user's tenant. The `tenant_id` is never accepted from request input — it is always resolved from the authenticated session.

On task creation, both `tenant_id` and `created_by` are set automatically in `TaskService`.

## Task Status

Tasks use a PHP Enum (`App\Enums\TaskStatus`):

```
todo → in_progress → done
```

## Caching

Task listings are cached in Redis per tenant. Cache is invalidated automatically on create, update, and delete.

## Queue

A `TaskActivityJob` is dispatched on every task create, update, and delete for async activity logging.

## Testing

```bash
php artisan test
```

Coverage areas:
- Authentication (register, login, logout)
- Task CRUD
- Tenant isolation (cross-tenant access is blocked)
- Validation
- Enum status transitions
- Search and filter
- Pagination
- Cache invalidation
- Queue dispatch
- API Resources
- Factories and Seeders

## License

MIT
