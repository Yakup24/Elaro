# Security Policy

## Supported Versions

The public repository tracks active development on `main`. Security fixes are applied to the latest public version.

| Version | Supported |
|---|---|
| `main` | Yes |

## Reporting a Vulnerability

Please do not open public issues for sensitive security reports.

Preferred reporting path:

- Contact the repository owners through GitHub profiles listed in `README.md`.
- Use a clear title such as `[ELARO-SECURITY] short summary`.

Please include:

- Affected component: API, Web, Mobile, CI, or documentation
- Reproduction steps
- Expected and actual behavior
- Possible impact
- Logs, screenshots, or proof of concept when safe to share

Expected response:

- Initial acknowledgement: within 72 hours
- Status update: within 7 days
- Critical fix target: within 14 days when reproducible

## Secret Handling

Never commit database credentials, API keys, tokens, publish profiles, `.env` files, local Android signing keys, or generated user settings.

Runtime secrets should be supplied through environment variables or host-level secret stores:

- `ConnectionStrings__DefaultConnection`
- `ELARO_DB_HOST`
- `ELARO_DB_NAME`
- `ELARO_DB_USER`
- `ELARO_DB_PASSWORD`
- `ELARO_API_BASE_URL`

If a secret is ever committed, rotate it immediately and rewrite public history before making the repository public.
