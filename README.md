# Elaro

Elaro is an e-commerce project with three parts:

- `ElaroAPI`: ASP.NET Core Web API
- `ElaroWeb`: PHP web frontend/admin panel
- `ElaroMobil`: Android mobile app

## Public Setup Notes

Secrets are intentionally not stored in the repository.

For the ASP.NET Core API, set the database connection string with:

```bash
ConnectionStrings__DefaultConnection="Server=...;Database=...;User ID=...;Password=...;Encrypt=True;TrustServerCertificate=False;"
```

For the PHP web app, copy `.env.example` values into your hosting environment and set:

```bash
ELARO_DB_HOST=
ELARO_DB_NAME=
ELARO_DB_USER=
ELARO_DB_PASSWORD=
ELARO_ADMIN_EMAIL=
```

For the Android app, override the API base URL with a Gradle property or environment variable:

```bash
ELARO_API_BASE_URL=https://your-api.example.com/
```

## License

This project is licensed under the MIT License.
