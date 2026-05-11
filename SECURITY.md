# Security Policy

## Supported Versions

The public repository tracks active development on the `main` branch. Security fixes are applied to the latest public version.

## Reporting a Vulnerability

Please do not open a public issue for sensitive security reports.

Report vulnerabilities by contacting the repository owner through GitHub. Include a short description, affected area, reproduction steps, and any logs or screenshots that help confirm the issue.

## Secret Handling

Do not commit database credentials, API keys, publish profiles, `.env` files, or generated user settings. Runtime secrets should be supplied with environment variables such as:

- `ConnectionStrings__DefaultConnection`
- `ELARO_DB_HOST`
- `ELARO_DB_NAME`
- `ELARO_DB_USER`
- `ELARO_DB_PASSWORD`
- `ELARO_ADMIN_EMAIL`
