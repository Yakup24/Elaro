## Summary

- 

## Type

- [ ] Feature
- [ ] Fix
- [ ] Docs
- [ ] Refactor
- [ ] Test
- [ ] CI / build

## Verification

- [ ] `dotnet test ElaroAPI/ElaroApi.sln -c Release`
- [ ] `php -l` for changed PHP files
- [ ] `./gradlew test assembleDebug`
- [ ] Secret scan has no findings

## Security Checklist

- [ ] No secrets, private URLs, keystores, or publish profiles are committed.
- [ ] Auth/authorization behavior is documented if changed.
- [ ] Database changes include schema or migration notes.
