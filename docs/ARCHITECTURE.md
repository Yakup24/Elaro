# Architecture

Elaro is a three-client e-commerce monorepo. The repository is intentionally split by runtime so each component can be built, tested and deployed independently.

```mermaid
flowchart TD
    Web["PHP Web UI"] --> Api["ASP.NET Core API"]
    Mobile["Android App"] --> Api
    Api --> Db["SQL Server / Azure SQL"]
```

## API

- Location: `ElaroAPI/ElaroApi`
- Runtime: ASP.NET Core 8
- Data access: EF Core SQL Server provider
- Security defaults: environment-based connection string, JWT bearer authentication, role claims, configured CORS origins, rate limiting, secure response headers, Swagger disabled in production by default
- Tests: `ElaroAPI/ElaroApi.Tests`

## Web

- Location: `ElaroWeb`
- Runtime: PHP
- Data access: PDO `sqlsrv`
- Security defaults: database credentials from environment variables, admin session guard, CSRF tokens, BCrypt-compatible password hashing, no persistent CVV storage

## Mobile

- Location: `ElaroMobil`
- Runtime: Android Kotlin
- Package id: `com.yakup24.elaro`
- Network configuration: API base URL is injected through `ELARO_API_BASE_URL` or Gradle property
- Networking: Retrofit and OkHttp

## Operational Boundaries

- Secrets never live in source control.
- Production configuration belongs in platform secret stores or environment variables.
- CI validates API tests, PHP syntax, Android buildability and known leaked values.
