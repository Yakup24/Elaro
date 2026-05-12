# Elaro

[![CI](https://github.com/Yakup24/Elaro/actions/workflows/ci.yml/badge.svg)](https://github.com/Yakup24/Elaro/actions/workflows/ci.yml)

Elaro is an e-commerce monorepo with an ASP.NET Core API, a PHP web/admin layer, and an Android client. The repository is prepared for public portfolio use with environment-based configuration, JWT authentication, CSRF protection, CI checks, and MIT licensing.

## Components

| Component | Stack | Location |
|---|---|---|
| API | ASP.NET Core 8, EF Core, SQL Server | `ElaroAPI/ElaroApi` |
| API tests | xUnit | `ElaroAPI/ElaroApi.Tests` |
| Web | PHP, PDO `sqlsrv` | `ElaroWeb` |
| Mobile | Android, Kotlin, Retrofit/OkHttp | `ElaroMobil` |
| Docs | Architecture, deployment, release notes | `docs` |

## Repository Layout

```text
Elaro/
|-- ElaroAPI/       # ASP.NET Core REST API and xUnit tests
|-- ElaroWeb/       # PHP web app and admin panel
|-- ElaroMobil/     # Android client
|-- database/       # SQL Server schema and seed scripts
|-- docs/           # Architecture, deployment and release docs
|-- .github/        # CI, Dependabot and ownership config
|-- docker-compose.yml
|-- .env.example
|-- LICENSE
`-- SECURITY.md
```

## Requirements

- .NET SDK 8
- PHP 8.2+ with `pdo_sqlsrv` / `sqlsrv`
- SQL Server 2022 or Azure SQL
- Android Studio with JDK 17
- Docker Desktop, optional

## Configuration

Secrets must stay outside the repository. Use `.env.example` as the key list and provide real values through environment variables, user secrets, GitHub Secrets, or your hosting provider.

API keys:

```bash
ConnectionStrings__DefaultConnection="Server=...;Database=...;User ID=...;Password=...;Encrypt=True;TrustServerCertificate=False;"
Jwt__Issuer="Elaro"
Jwt__Audience="ElaroClients"
Jwt__Key="replace-with-a-32-byte-minimum-random-secret"
Jwt__AccessTokenMinutes="60"
Cors__AllowedOrigins__0="http://localhost"
```

PHP keys:

```bash
ELARO_DB_HOST=
ELARO_DB_NAME=
ELARO_DB_USER=
ELARO_DB_PASSWORD=
```

Android key:

```bash
ELARO_API_BASE_URL=https://your-api.example.com/
```

## Quick Start

Restore and test the API:

```bash
dotnet restore ElaroAPI/ElaroApi.sln
dotnet test ElaroAPI/ElaroApi.sln -c Release
dotnet run --project ElaroAPI/ElaroApi
```

Run the PHP web layer:

```bash
php -S localhost:8080 -t ElaroWeb
```

Build the Android app:

```bash
cd ElaroMobil
./gradlew assembleDebug
```

Start the local Docker stack:

```bash
docker compose up --build
```

## Database

The portable SQL Server bootstrap files live in `database/`.

```bash
sqlcmd -S localhost,1433 -U sa -P "<password>" -i database/schema.sql
sqlcmd -S localhost,1433 -U sa -P "<password>" -d Elaro -i database/seed.sql
```

## Security

- Database credentials, JWT keys, publish profiles, keystores and `.env` files are ignored.
- API authentication uses JWT bearer tokens and role claims for customer/admin access.
- API login and general endpoints are rate limited.
- CORS is configured with explicit allowed origins.
- API and PHP password hashes use BCrypt-compatible storage.
- PHP admin authorization uses the `Musteri2.Role = Admin` database role instead of an environment-selected email.
- PHP forms use CSRF tokens and hardened session cookie settings.
- Payment card numbers are masked and CVV values must not be stored permanently.
- Known leaked deployment values are blocked by the CI secret guard.
- PHP route filenames are ASCII-safe for cross-platform deploys.

Report vulnerabilities through [SECURITY.md](SECURITY.md).

## Documentation

- [Architecture](docs/ARCHITECTURE.md)
- [Deployment](docs/DEPLOYMENT.md)
- [Release checklist](docs/RELEASE_CHECKLIST.md)
- [Latest release notes](docs/releases/v0.2.2.md)
- [Changelog](CHANGELOG.md)
- [Contributing](CONTRIBUTING.md)
- [Code of Conduct](CODE_OF_CONDUCT.md)

## Authors

- [Yakup Eski](https://github.com/Yakup24)
- [Berat Kurucay](https://github.com/BeratKurucay)

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE).
