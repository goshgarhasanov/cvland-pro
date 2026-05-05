<div align="center">

# CVLAND PRO

**Production-grade CV builder · Laravel 11 API · Vue 3 SPA**

[![CI](https://github.com/goshgarhasanov/cvland-pro/actions/workflows/ci.yml/badge.svg)](https://github.com/goshgarhasanov/cvland-pro/actions/workflows/ci.yml)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Vue](https://img.shields.io/badge/Vue-3-4FC08D?logo=vuedotjs&logoColor=white)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?logo=typescript&logoColor=white)](https://www.typescriptlang.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

</div>

---

## Why this project exists

HR managers spend an average of **7 seconds** reviewing each CV. Candidates with professionally-formatted CVs receive **40% more callbacks**. CVLAND PRO is an opinionated, end-to-end web application that lets users build, preview, and export ATS-friendly CVs in minutes — and it's engineered as a reference for what a senior-level Laravel + Vue codebase looks like in 2026.

This is **not** a tutorial project. It is structured the way you would structure a real product: domain-oriented backend, fully typed frontend, automated tests, containerised dev environment, and CI/CD on every commit.

## Highlights

- **Domain-Driven Architecture** — backend is organised around bounded contexts (`CV`, `Billing`, `Identity`, `Catalog`), not Laravel's default flat MVC.
- **Action-based service layer** — single-responsibility classes for every business operation. Controllers stay thin; logic stays testable.
- **API-first** — Laravel exposes a versioned REST API (`/api/v1`). The Vue SPA is one of N possible clients.
- **Sanctum SPA auth** — first-party cookie auth with CSRF protection, plus token-based auth for third-party clients.
- **Type-safe end to end** — strict TypeScript on the frontend, PHP 8.3 readonly DTOs and enums on the backend, generated OpenAPI types via Scramble.
- **Async by default** — PDF generation, email, and analytics run on Laravel queues (Redis-backed).
- **Tested at every layer** — Pest 3 for the API, Vitest for the frontend, Playwright for end-to-end. CI runs them all.
- **One-command dev environment** — `docker compose up` boots PHP-FPM, nginx, MySQL, Redis, MailHog, and the Vite dev server.

## Tech stack

### Backend (`api/`)

| Concern | Choice |
| --- | --- |
| Framework | Laravel 11 (PHP 8.3) |
| Auth | Laravel Sanctum (SPA cookies + API tokens) |
| Database | MySQL 8 (production) · SQLite (testing) |
| Queue / cache | Redis 7 |
| Storage | S3-compatible (MinIO in dev) |
| PDF | Browsershot (headless Chrome) |
| Search | Laravel Scout + Meilisearch |
| Static analysis | Larastan (PHPStan level 8) |
| Code style | Laravel Pint |
| Testing | Pest 3 (Feature + Unit + Architecture) |
| API docs | Scramble (auto-generated OpenAPI 3.1) |

### Frontend (`web/`)

| Concern | Choice |
| --- | --- |
| Framework | Vue 3 (Composition API, `<script setup>`) |
| Language | TypeScript 5 (strict) |
| Build | Vite 5 |
| Routing | Vue Router 4 (lazy chunks) |
| State | Pinia |
| Server state | TanStack Query |
| Forms | VeeValidate + Zod |
| Styling | Tailwind CSS v4 |
| Components | shadcn-vue |
| HTTP | Axios (interceptors, token refresh) |
| i18n | vue-i18n (AZ / EN / RU) |
| Testing | Vitest + Vue Test Utils · Playwright |

### DevOps

| Concern | Choice |
| --- | --- |
| Containerisation | Docker Compose |
| CI | GitHub Actions (lint → test → build) |
| Errors | Sentry |
| Commits | Conventional Commits + Husky + lint-staged |

## Architecture at a glance

```
                          ┌──────────────────┐
                          │   Vue 3 SPA      │
                          │   (Vite, TS)     │
                          └────────┬─────────┘
                                   │ HTTPS / cookies
                          ┌────────▼─────────┐
                          │  Laravel API     │
                          │  /api/v1/*       │
                          └────────┬─────────┘
              ┌────────────────────┼──────────────────────┐
              │                    │                      │
       ┌──────▼──────┐      ┌──────▼──────┐       ┌───────▼───────┐
       │   MySQL     │      │   Redis     │       │   S3 / MinIO  │
       │ (system of  │      │ (cache +    │       │  (PDFs +      │
       │  record)    │      │  queues)    │       │   uploads)    │
       └─────────────┘      └─────────────┘       └───────────────┘
                                   │
                          ┌────────▼─────────┐
                          │  Queue workers   │
                          │  · GeneratePdf   │
                          │  · SendEmail     │
                          │  · ChargeCard    │
                          └──────────────────┘
```

## Domain layout (backend)

```
api/app/
├── Domain/
│   ├── Identity/          # users, profiles, OAuth
│   ├── CV/                # cv documents, sections, versions
│   ├── Catalog/           # templates, categories
│   └── Billing/           # orders, payments, balance
├── Http/
│   ├── Controllers/Api/V1/
│   ├── Requests/          # form requests (validation)
│   └── Resources/         # API resources (response shape)
└── Support/               # generic infrastructure
```

Each domain folder contains its own `Models/`, `Actions/`, `DTOs/`, `Events/`, and `Policies/` — keeping a feature's code colocated rather than scattered across Laravel's default top-level folders.

## Getting started

### Prerequisites

- Docker Desktop (recommended), **or**
- PHP 8.3, Composer 2, Node 20+, MySQL 8, Redis 7

### With Docker (recommended)

```bash
git clone https://github.com/goshgarhasanov/cvland-pro.git
cd cvland-pro
cp api/.env.example api/.env
docker compose up -d
docker compose exec api php artisan key:generate
docker compose exec api php artisan migrate --seed
```

API → http://localhost:8000 · Web → http://localhost:5173 · MailHog → http://localhost:8025

### Without Docker

```bash
# API
cd api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve

# Web (in a second terminal)
cd web
npm install
npm run dev
```

## Running the test suite

```bash
# Backend — Pest
cd api && composer test            # unit + feature
composer test:coverage             # with coverage report
composer analyse                   # Larastan level 8
composer pint                      # code style

# Frontend — Vitest + Playwright
cd web && npm test                 # unit
npm run test:e2e                   # end-to-end
npm run lint                       # ESLint + Prettier
npm run typecheck                  # tsc --noEmit
```

## Project structure

```
cvland-pro/
├── api/                  # Laravel 11 application
├── web/                  # Vue 3 SPA
├── docker/               # Dockerfiles + nginx config
├── docker-compose.yml
├── .github/workflows/    # CI pipelines
└── docs/                 # ADRs and architecture notes
```

## Roadmap

- [x] Project skeleton (API + SPA + Docker + CI)
- [ ] Identity domain — register, login, email verify, password reset
- [ ] OAuth (Google)
- [ ] CV builder wizard (10 steps)
- [ ] Template marketplace (5+ templates)
- [ ] Real-time preview
- [ ] PDF export (Browsershot)
- [ ] Stripe integration + balance
- [ ] Filament admin panel
- [ ] Public CV share links
- [ ] Multi-language (AZ / EN / RU)

## License

[MIT](LICENSE) © 2026 Goshgar Hasanov
