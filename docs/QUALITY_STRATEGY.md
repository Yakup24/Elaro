# Quality Strategy

This document describes the quality strategy for the Elaro monorepo.

Elaro contains multiple runtimes: ASP.NET Core API, PHP web/admin layer, Android client and SQL Server scripts. The quality strategy is therefore designed around layered validation instead of relying on a single test type.

## Quality Goals

1. Keep the API buildable and testable.
2. Keep PHP files syntactically valid across platforms.
3. Keep the Android client buildable.
4. Prevent known secrets and private deployment values from re-entering the repository.
5. Make release readiness visible through repeatable commands and CI checks.

## Validation Layers

### 1. API validation

The API layer is validated with .NET restore, build and xUnit tests.

```bash
dotnet restore ElaroAPI/ElaroApi.sln
dotnet build ElaroAPI/ElaroApi.sln --no-restore -c Release
dotnet test ElaroAPI/ElaroApi.sln --no-build -c Release
```

Recommended API test coverage areas:

- Authentication and authorization
- Product read operations
- Cart operations
- Order creation and status transitions
- Input validation
- Error response consistency
- Rate-limited endpoints

### 2. PHP validation

The PHP layer is validated with syntax linting in CI.

```bash
find ElaroWeb -name "*.php" -print0 | xargs -0 -n1 php -l
```

Recommended future PHP checks:

- Form submission smoke tests
- CSRF token validation tests
- Admin guard tests
- Session hardening checks

### 3. Android validation

The Android layer is validated with debug build and unit tests.

```bash
cd ElaroMobil
./gradlew assembleDebug
./gradlew test
```

Recommended future Android checks:

- API client unit tests
- ViewModel tests
- UI smoke tests
- Offline/error-state tests

### 4. Database validation

The database layer currently provides portable schema and seed scripts.

Recommended validation flow:

```bash
sqlcmd -S localhost,1433 -U sa -P "<password>" -i database/schema.sql
sqlcmd -S localhost,1433 -U sa -P "<password>" -d Elaro -i database/seed.sql
```

Recommended future checks:

- Schema drift detection
- Disposable SQL Server container integration tests
- Seed data validation
- Migration rollback checks

### 5. Secret guard

CI includes a secret guard that blocks known leaked values and private deployment strings.

This is not a replacement for a full secret scanning service, but it provides a strong repository-specific safety net.

Recommended future checks:

- Gitleaks
- detect-secrets
- GitHub secret scanning
- Dependency vulnerability audit

## CI Quality Gates

Current GitHub Actions workflow validates:

| Gate | Purpose |
|---|---|
| API restore/build/test | Verifies backend build and unit tests |
| PHP lint | Verifies PHP syntax |
| Android build/test | Verifies mobile buildability |
| Secret guard | Blocks known private values |
| Artifact upload | Preserves API test results |

## Definition of Done

A change is considered ready when:

- API solution builds successfully.
- API tests pass.
- PHP lint passes.
- Android debug build passes.
- Android unit tests pass.
- Secret guard passes.
- README/docs are updated when behavior changes.
- No real credentials or personal data are committed.

## Portfolio Review Checklist

For public portfolio review, each release should clearly show:

- What problem the project solves
- Which runtimes are included
- How to configure it safely
- How to run tests
- What CI validates
- What security decisions were made
- What is intentionally left as future work

## Recommended Next Quality Improvements

1. Add OpenAPI generation and contract snapshot checks.
2. Add disposable SQL Server integration tests.
3. Add PHP form smoke tests.
4. Add Android API client tests with mocked responses.
5. Add dependency and secret scanning workflows.
6. Add coverage reports and badges.
7. Add ADR files for major architecture decisions.
