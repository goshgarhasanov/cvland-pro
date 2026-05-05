<div align="center">

# CVLAND PRO

**Production-grade CV builder · Laravel 13 API · Vue 3 SPA · Filament 4 admin**

[![CI](https://github.com/goshgarhasanov/cvland-pro/actions/workflows/ci.yml/badge.svg)](https://github.com/goshgarhasanov/cvland-pro/actions/workflows/ci.yml)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Vue](https://img.shields.io/badge/Vue-3.5-4FC08D?logo=vuedotjs&logoColor=white)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.6-3178C6?logo=typescript&logoColor=white)](https://www.typescriptlang.org)
[![Filament](https://img.shields.io/badge/Filament-4-FFBA37?logo=php&logoColor=white)](https://filamentphp.com)
[![Tests](https://img.shields.io/badge/Tests-29%2F29%20passing-success)](#)
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
- **Tested at every layer** — Pest 3 for the API (29 tests, including dedicated security and architecture suites), Vitest for the frontend, Playwright for end-to-end. CI runs them all.
- **Production-hardened** — explicit policy authorization on every controller action, named rate limiters per endpoint (5/min login, 3/min register), `SecurityHeaders` middleware (HSTS, CSP, X-Frame-Options, Referrer-Policy, Permissions-Policy), env-driven CORS allowlist, env-driven trusted proxies, open-redirect guard on the SPA. `composer audit` and `npm audit --omit=dev` both clean.
- **Filament 4 admin panel** — dark-mode-default, fully native Azerbaijani UI, four resources (İstifadəçilər, CV-lər, Şablonlar, Sifarişlər) with stats and recent-records widgets.
- **One-command dev environment** — `docker compose up` boots PHP-FPM, nginx, MySQL, Redis, MailHog, MinIO, and the Vite dev server.

## Screenshots

> All screenshots are dark-mode by default and the UI ships entirely in native Azerbaijani.

### Marketing site

| Hero | Full page |
| :---: | :---: |
| ![Home hero](docs/screenshots/01-home-hero.png) | ![Home full](docs/screenshots/02-home-full.png) |
| Hero with animated CV mockup | Marketing page (Hero → Stats → How it works → Features → Templates → Testimonials → FAQ → CTA → Footer) |

### Auth & mobile

| Login | Register | Mobile |
| :---: | :---: | :---: |
| ![Login](docs/screenshots/03-login.png) | ![Register](docs/screenshots/04-register.png) | ![Mobile hero](docs/screenshots/05-mobile-hero.png) |
| Premium split-screen, password show/hide, social proof | Strength meter, accessible inline icons, AZ validation messages | Fully responsive — iPhone 14 Pro viewport |

### Admin panel (Filament 4)

| Dashboard | Templates | CVs |
| :---: | :---: | :---: |
| ![Admin dashboard](docs/screenshots/09-admin-dashboard.png) | ![Templates](docs/screenshots/11-admin-templates.png) | ![CVs](docs/screenshots/12-admin-cvs.png) |
| Stats overview + latest orders + latest CVs | 5 seeded templates with category, price, active toggle | CV resource with status filter and template relation |

| Users | Orders | API docs |
| :---: | :---: | :---: |
| ![Users](docs/screenshots/10-admin-users.png) | ![Orders](docs/screenshots/13-admin-orders.png) | ![API docs](docs/screenshots/14-api-docs.png) |
| User resource with locale + verification filters | Orders with status, payment method, paid filter | Auto-generated OpenAPI 3.1 via Scramble |

> Reproducing these screenshots: `cd web && node scripts/screenshots.mjs` while both servers are running. The script uses Playwright's Chromium and writes to `docs/screenshots/`.

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

API → http://localhost:8000 · Web → http://localhost:5173 · Admin → http://localhost:8000/admin · MailHog → http://localhost:8025

Default admin credentials (seeded for local dev only): **`admin@admin.com` / `admin`**.

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
├── api/                  # Laravel 13 application + Filament 4 admin
├── web/                  # Vue 3 SPA
├── docker/               # Dockerfiles + nginx config
├── docker-compose.yml
├── .github/workflows/    # CI pipelines
└── docs/
    └── screenshots/      # PNGs used in this README
```

## Roadmap

- [x] Project skeleton (API + SPA + Docker + CI)
- [x] Identity domain — register, login, logout, `/me`, email verify (signed)
- [x] Sanctum SPA cookie auth + named per-endpoint rate limiters
- [x] Template marketplace skeleton (5 seeded templates)
- [x] Filament 4 admin panel (4 resources + 3 dashboard widgets)
- [x] Native Azerbaijani UI (frontend + admin) with vue-i18n
- [x] Security hardening (OWASP audit, headers, IDOR fix, CORS allowlist)
- [x] Marketing website (Hero, Features, Templates, Testimonials, FAQ, CTA)
- [ ] OAuth (Google)
- [ ] CV builder wizard (10 steps) — frontend UX
- [ ] Real-time preview
- [ ] PDF export (Browsershot)
- [ ] Stripe integration + balance
- [ ] Public CV share links

## License

[MIT](LICENSE) © 2026 Goshgar Hasanov
