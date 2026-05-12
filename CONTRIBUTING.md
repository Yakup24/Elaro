# Contributing to Elaro

Thanks for taking the time to improve Elaro. This repository contains three runtimes, so keep changes focused and easy to review.

## Development Setup

1. Fork or clone the repository.
2. Create a branch with a clear name:

```bash
git checkout -b feat/short-description
```

3. Configure secrets locally through environment variables or `.env`; never commit real credentials.
4. Run the relevant checks before opening a pull request:

```bash
dotnet test ElaroAPI/ElaroApi.sln -c Release
```

```bash
cd ElaroMobil
./gradlew test assembleDebug
```

```bash
php -l ElaroWeb/index.php
```

## Commit Style

Use Conventional Commits:

```text
feat(api): add product filter endpoint
fix(web): validate csrf token on checkout
docs: update deployment guide
test(api): cover jwt token claims
chore(deps): update android dependencies
```

## Pull Request Rules

- Keep the PR scoped to one concern.
- Describe what changed and why.
- Include test evidence in the PR body.
- Update `README.md`, `CHANGELOG.md`, or docs when behavior changes.
- Do not include `.env`, keystores, publish profiles, database passwords, JWT keys, or private URLs.

## Security Reports

Do not open public issues for security vulnerabilities. Follow `SECURITY.md`.
