# Operations Runbook

This runbook explains how to validate and operate the Elaro monorepo locally or in CI.

## 1. Repository setup

```bash
git clone https://github.com/Yakup24/Elaro.git
cd Elaro
```

Copy the example environment file and provide local values outside source control:

```bash
cp .env.example .env
```

Never commit `.env`, publish profiles, keystores, real connection strings, API keys or payment credentials.

## 2. API validation

Restore and test the API solution:

```bash
dotnet restore ElaroAPI/ElaroApi.sln
dotnet build ElaroAPI/ElaroApi.sln --no-restore -c Release
dotnet test ElaroAPI/ElaroApi.sln --no-build -c Release
```

Run the API locally:

```bash
dotnet run --project ElaroAPI/ElaroApi
```

Common API issues:

| Symptom | Likely cause | Action |
|---|---|---|
| Database connection fails | Missing/incorrect connection string | Check `ConnectionStrings__DefaultConnection` |
| JWT validation fails | Missing or short JWT key | Set a strong `Jwt__Key` |
| CORS error | Origin not allowed | Add origin under `Cors__AllowedOrigins` |
| Swagger unavailable | Production defaults | Run in development environment if needed |

## 3. PHP web/admin validation

Run syntax checks:

```bash
find ElaroWeb -name "*.php" -print0 | xargs -0 -n1 php -l
```

Run locally:

```bash
php -S localhost:8080 -t ElaroWeb
```

Common PHP issues:

| Symptom | Likely cause | Action |
|---|---|---|
| SQL Server connection fails | `pdo_sqlsrv` missing | Install PHP SQL Server extensions |
| Admin page redirects | User role is not Admin | Check `Musteri2.Role` value |
| Form submit rejected | CSRF token invalid | Refresh form/session |
| Session issue | Cookie/security setting mismatch | Check local PHP/session config |

## 4. Android validation

```bash
cd ElaroMobil
chmod +x gradlew
./gradlew assembleDebug
./gradlew test
```

Common Android issues:

| Symptom | Likely cause | Action |
|---|---|---|
| API calls fail | Base URL missing/wrong | Set `ELARO_API_BASE_URL` or Gradle property |
| Build fails | JDK mismatch | Use JDK 17 |
| Network cleartext blocked | HTTP endpoint | Use HTTPS or Android network config for local dev |

## 5. Database bootstrap

```bash
sqlcmd -S localhost,1433 -U sa -P "<password>" -i database/schema.sql
sqlcmd -S localhost,1433 -U sa -P "<password>" -d Elaro -i database/seed.sql
```

Recommended local approach:

1. Start SQL Server locally or through Docker.
2. Apply schema.
3. Apply seed data.
4. Configure API/PHP connection strings.
5. Run API tests or manual smoke checks.

## 6. Docker stack

```bash
docker compose up --build
```

Use this for local integration-style validation when Docker is available.

## 7. CI behavior

GitHub Actions validates:

1. API restore/build/test
2. PHP lint
3. Android debug build and unit tests
4. Secret guard
5. API test result artifact upload

A failure in any job should block merging until fixed.

## 8. Release checklist

Before tagging a release:

- [ ] API tests pass
- [ ] PHP lint passes
- [ ] Android build/tests pass
- [ ] Secret guard passes
- [ ] Database scripts are reviewed
- [ ] `.env.example` reflects required configuration keys
- [ ] Documentation is updated
- [ ] `CHANGELOG.md` is updated
- [ ] Release notes are added under `docs/releases/`

## 9. Incident response for leaked values

If a secret or deployment value is committed:

1. Revoke or rotate the value immediately.
2. Remove it from the repository.
3. Add a guard pattern if it is project-specific.
4. Review Git history exposure.
5. Update `SECURITY.md` if needed.

## 10. Portfolio demo script

Recommended demo order for reviewers:

1. Show README overview and architecture diagram.
2. Show CI workflow and passing jobs.
3. Run API tests.
4. Show PHP lint command.
5. Show Android build command.
6. Explain secret handling and role-based admin authorization.
7. Show database schema/seed files.
8. Explain roadmap and known trade-offs.
