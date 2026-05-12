# Deployment

This guide describes the public-demo deployment path for Elaro.

## Local Docker Stack

Create a local `.env` file from `.env.example` and provide at least:

```dotenv
ELARO_SQL_PASSWORD=ChangeMe_12345!
JWT_KEY=replace_with_a_real_32_byte_minimum_secret
```

Start the stack:

```bash
docker compose up --build
```

The API listens on:

```text
http://localhost:5218
```

Health check:

```bash
curl http://localhost:5218/health
```

## Database Bootstrap

After SQL Server starts, apply the schema and seed data:

```bash
sqlcmd -S localhost,1433 -U sa -P "$ELARO_SQL_PASSWORD" -i database/schema.sql
sqlcmd -S localhost,1433 -U sa -P "$ELARO_SQL_PASSWORD" -d Elaro -i database/seed.sql
```

## Production Rules

- Use platform secrets for connection strings and JWT keys.
- Rotate any credential that has ever been committed.
- Keep `Swagger:EnableInProduction` disabled unless the API is private or authenticated.
- Set CORS origins to real frontend domains only.
- Use HTTPS at the ingress or reverse proxy layer.
- Keep SQL Server behind a private network whenever possible.

## Release Flow

```bash
dotnet test ElaroAPI/ElaroApi.sln -c Release
cd ElaroMobil
./gradlew assembleDebug
```

Then tag:

```bash
git tag -a v0.2.0 -m "Elaro v0.2.0"
git push origin v0.2.0
```
