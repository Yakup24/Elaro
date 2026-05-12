FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src

COPY ElaroAPI/ElaroApi.sln ElaroAPI/
COPY ElaroAPI/ElaroApi/ElaroApi.csproj ElaroAPI/ElaroApi/
COPY ElaroAPI/ElaroApi.Tests/ElaroApi.Tests.csproj ElaroAPI/ElaroApi.Tests/
RUN dotnet restore ElaroAPI/ElaroApi.sln

COPY . .
RUN dotnet publish ElaroAPI/ElaroApi/ElaroApi.csproj -c Release -o /app/publish --no-restore

FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS runtime
WORKDIR /app

ENV ASPNETCORE_URLS=http://+:8080
EXPOSE 8080

COPY --from=build /app/publish .
ENTRYPOINT ["dotnet", "ElaroApi.dll"]
