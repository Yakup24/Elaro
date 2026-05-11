# Release Checklist

Use this checklist before opening the repository publicly or publishing a release.

## Security

- Rotate database passwords and deployment credentials.
- Confirm `.env`, publish profiles, keystores, and user settings are not tracked.
- Run the secret guard search locally:

```bash
rg --hidden -n -i "(known-leaked-password|known-db-user|known-private-host|publish-profile-marker)" .
```

- Confirm `ConnectionStrings__DefaultConnection` is supplied only by the runtime environment.
- Confirm API CORS origins are set to the actual frontend origins.

## Build

```bash
dotnet restore ElaroAPI/ElaroApi.sln
dotnet build ElaroAPI/ElaroApi.sln --no-restore
```

```bash
php -l ElaroWeb/index.php
```

```bash
cd ElaroMobil
./gradlew assembleDebug
```

## GitHub

- CI workflow is green.
- Dependabot is enabled.
- `SECURITY.md` is visible on the repository security tab.
- `LICENSE` is detected as MIT.
- README has setup, architecture, security, and author links.

## Release

- Tag format: `v0.1.0`
- Release title: `Elaro v0.1.0`
- Include API, Web, Mobile, security, and breaking-change notes.
