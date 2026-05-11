# Elaro

[![CI](https://github.com/Yakup24/Elaro/actions/workflows/ci.yml/badge.svg)](https://github.com/Yakup24/Elaro/actions/workflows/ci.yml)

Elaro; ASP.NET Core API, PHP web arayuzu ve Android istemcisinden olusan bir e-ticaret monorepo'sudur. Proje, portfoy ve gelistirme amacli olarak guvenli varsayilanlar, ortam degiskeni tabanli secret yonetimi ve temel CI kontrolleriyle hazirlanmistir.

## Bilesenler

| Bilesen | Teknoloji | Konum |
|---|---|---|
| API | ASP.NET Core 8, EF Core, SQL Server | `ElaroAPI/ElaroApi` |
| Web | PHP, PDO `sqlsrv` | `ElaroWeb` |
| Mobil | Android, Kotlin, Retrofit/OkHttp | `ElaroMobil` |

## Dizin Yapisi

```text
Elaro/
├─ ElaroAPI/      # REST API
├─ ElaroWeb/      # PHP web uygulamasi ve admin panel
├─ ElaroMobil/    # Android istemcisi
├─ docs/          # Mimari ve release notlari
├─ .github/       # CI, Dependabot ve repo sahipligi
├─ .env.example
├─ LICENSE
└─ SECURITY.md
```

## Gereksinimler

- .NET SDK 8
- PHP 8.2+ ve `pdo_sqlsrv` / `sqlsrv` eklentileri
- SQL Server veya Azure SQL
- Android Studio + JDK 17

## Ortam Degiskenleri

Secret degerleri repoya yazilmaz. Baslangic icin `.env.example` dosyasini referans alin.

API icin:

```bash
ConnectionStrings__DefaultConnection="Server=...;Database=...;User ID=...;Password=...;Encrypt=True;TrustServerCertificate=False;"
Cors__AllowedOrigins__0="http://localhost"
```

PHP web icin:

```bash
ELARO_DB_HOST=
ELARO_DB_NAME=
ELARO_DB_USER=
ELARO_DB_PASSWORD=
ELARO_ADMIN_EMAIL=
```

Android icin:

```bash
ELARO_API_BASE_URL=https://your-api.example.com/
```

## Calistirma

API:

```bash
dotnet restore ElaroAPI/ElaroApi.sln
dotnet run --project ElaroAPI/ElaroApi
```

PHP web:

```bash
php -S localhost:8080 -t ElaroWeb
```

Android:

```bash
cd ElaroMobil
./gradlew assembleDebug
```

Windows icin:

```powershell
cd ElaroMobil
.\gradlew.bat assembleDebug
```

## Dogrulama

```bash
dotnet build ElaroAPI/ElaroApi.sln --no-restore
```

CI pipeline'i API build, PHP lint, Android debug build ve secret guard kontrollerini calistirir.

## Guvenlik Notlari

- Veritabani credential'lari ve publish profile dosyalari repoda tutulmaz.
- API CORS origin'leri whitelist olarak konfigure edilir.
- API auth endpoint'leri ve genel endpoint'ler icin rate limiting vardir.
- PHP tarafinda sifreler `password_hash` ile saklanir ve eski duz metin sifreler giris sirasinda hash'e tasinir.
- Kart/CVV bilgisi kalici olarak saklanmaz; kart numarasi maskelenir.

Guvenlik acigi bildirmek icin [SECURITY.md](SECURITY.md) dosyasini takip edin.

## Dokumantasyon

- [Mimari](docs/ARCHITECTURE.md)
- [Release checklist](docs/RELEASE_CHECKLIST.md)

## Authors

- [Yakup Eşki](https://github.com/Yakup24)
- [Berat Kuruçay](https://github.com/BeratKurucay)

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE).
