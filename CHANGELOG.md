# Changelog

All notable changes to this project are documented here.

The format follows Keep a Changelog style and this project uses semantic versioning for public releases.

## [0.2.0] - 2026-05-12

### Added

- JWT authentication and role-aware API token responses.
- Rate limiting, CORS hardening and security headers for the API.
- PHP CSRF helpers and secure session defaults.
- GitHub Actions CI for API, PHP, Android and secret scanning.
- Dependabot grouping for NuGet, Gradle and GitHub Actions.
- Docker Compose local stack for API plus SQL Server.
- SQL Server schema and seed scripts for portable demos.
- xUnit test project for API security-critical services.
- Contribution guide, code of conduct, issue templates and pull request template.

### Changed

- Android package id moved to `com.yakup24.elaro`.
- Android networking dependencies are standardized around Retrofit and OkHttp.
- API and PHP password hashing are aligned around BCrypt-compatible hashes.
- PHP admin access now uses the `Musteri2.Role = Admin` database role.
- README is rewritten for public portfolio use.

### Security

- Removed hard-coded deployment credentials from repository configuration.
- Added known-secret guard checks to CI.
- Added BCrypt verification for API customer/admin login flows.

## [0.1.0] - 2026-05-12

### Added

- Initial Elaro API, PHP web layer and Android client monorepo structure.
- MIT license, security policy and environment variable template.
