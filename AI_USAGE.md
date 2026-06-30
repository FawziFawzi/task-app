# AI Usage

This document discloses how AI tools were used during the development of this project.

## Tool

**Claude Code** (Anthropic) — `claude-sonnet-4-6`

## Scope of Use

AI was used to assist with implementing code based on a developer-defined architecture and execution plan.

| Area                | How AI Was Used                                                                                                                  |
| ------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| Project scaffolding | Generating Laravel boilerplate for models, migrations, routes, and basic project structure following the provided execution plan |
| Service Layer       | Drafting `AuthService` and `TaskService` implementations                                                                         |
| Multi-tenancy       | Implementing the Global Scope pattern for tenant isolation                                                                       |
| Authentication      | Assisting with Sanctum authentication implementation                                                                             |
| Cache & Queue       | Implementing Redis caching and the `TaskActivityJob` queue job                                                                   |
| README              | Assisting with project documentation                                                                                             |

## Developer Contributions

The following were designed and specified by the developer before implementation:

* Project architecture.
* Complete execution plan and implementation order.
* Database schema and relationships.
* Service Layer architecture.
* Form Request layer.
* API Resources.
* PHP Enums (`TaskStatus`).
* Model factories.
* Database seeders.
* Redis integration (cache and queues).
* Multi-tenancy approach and tenant isolation rules.
* Feature requirements and project decisions.

AI implemented code according to these specifications.

## What AI Did Not Do

* AI did not define the project architecture.
* AI did not define the database schema.
* AI did not choose the technology stack.
* AI did not make business logic or design decisions.
* AI followed the developer's execution plan and implementation instructions.
* All generated code was reviewed, tested, and adjusted by the developer before acceptance.

## Prompting Approach

The developer created a detailed execution plan that defined the architecture, technology stack, database schema, coding standards, and implementation phases. Claude Code was then instructed to implement the project step by step according to that plan.

## Review

Every generated file was inspected, tested, and refined by the developer to ensure correctness, security, maintainability, and compliance with the project's architectural decisions.
