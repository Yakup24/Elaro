using ElaroApi.Data;
using Microsoft.AspNetCore.RateLimiting;
using Microsoft.EntityFrameworkCore;
using System.Threading.RateLimiting;

var builder = WebApplication.CreateBuilder(args);
var connectionString = builder.Configuration.GetConnectionString("DefaultConnection");
var allowedOrigins = builder.Configuration
    .GetSection("Cors:AllowedOrigins")
    .GetChildren()
    .Select(origin => origin.Value)
    .Where(origin => !string.IsNullOrWhiteSpace(origin))
    .Select(origin => origin!)
    .ToArray();

if (string.IsNullOrWhiteSpace(connectionString))
{
    throw new InvalidOperationException(
        "DefaultConnection is not configured. Set ConnectionStrings__DefaultConnection as an environment variable.");
}

builder.Services.AddControllers();

builder.Services.AddDbContext<AppDbContext>(options =>
    options.UseSqlServer(connectionString));

builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen(options =>
{
    options.SwaggerDoc("v1", new()
    {
        Title = "ElaroAPI",
        Version = "v1",
        Description = "Elaro e-commerce Web API"
    });
});

builder.Services.AddCors(options =>
{
    options.AddPolicy("ConfiguredOrigins", policy =>
    {
        if (allowedOrigins.Length > 0)
        {
            policy.WithOrigins(allowedOrigins)
                  .AllowAnyHeader()
                  .AllowAnyMethod();
        }
    });
});

builder.Services.AddRateLimiter(options =>
{
    options.RejectionStatusCode = StatusCodes.Status429TooManyRequests;
    options.GlobalLimiter = PartitionedRateLimiter.Create<HttpContext, string>(_ =>
        RateLimitPartition.GetSlidingWindowLimiter("global-api", _ =>
            new SlidingWindowRateLimiterOptions
            {
                PermitLimit = builder.Configuration.GetValue("RateLimiting:ApiPermitLimit", 120),
                Window = TimeSpan.FromMinutes(1),
                SegmentsPerWindow = 4,
                QueueLimit = 0,
                QueueProcessingOrder = QueueProcessingOrder.OldestFirst
            }));

    options.AddFixedWindowLimiter("Auth", limiter =>
    {
        limiter.PermitLimit = builder.Configuration.GetValue("RateLimiting:AuthPermitLimit", 10);
        limiter.Window = TimeSpan.FromMinutes(1);
        limiter.QueueLimit = 0;
        limiter.QueueProcessingOrder = QueueProcessingOrder.OldestFirst;
    });

    options.AddSlidingWindowLimiter("Api", limiter =>
    {
        limiter.PermitLimit = builder.Configuration.GetValue("RateLimiting:ApiPermitLimit", 120);
        limiter.Window = TimeSpan.FromMinutes(1);
        limiter.SegmentsPerWindow = 4;
        limiter.QueueLimit = 0;
        limiter.QueueProcessingOrder = QueueProcessingOrder.OldestFirst;
    });
});

var app = builder.Build();

if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/error");
    app.UseHsts();
}

app.Use(async (context, next) =>
{
    context.Response.Headers.TryAdd("X-Content-Type-Options", "nosniff");
    context.Response.Headers.TryAdd("X-Frame-Options", "DENY");
    context.Response.Headers.TryAdd("Referrer-Policy", "no-referrer");
    context.Response.Headers.TryAdd("Permissions-Policy", "camera=(), microphone=(), geolocation=()");
    context.Response.Headers.TryAdd("Cross-Origin-Opener-Policy", "same-origin");
    await next();
});

var enableSwagger = app.Environment.IsDevelopment()
    || app.Configuration.GetValue<bool>("Swagger:EnableInProduction");

if (enableSwagger)
{
    app.UseSwagger();
    app.UseSwaggerUI(c =>
    {
        c.SwaggerEndpoint("/swagger/v1/swagger.json", "ElaroAPI v1");
        c.RoutePrefix = "swagger";
    });
}

app.UseHttpsRedirection();
app.UseCors("ConfiguredOrigins");
app.UseRateLimiter();

app.MapGet("/health", () => Results.Ok(new
{
    status = "ok",
    service = "ElaroAPI",
    utc = DateTimeOffset.UtcNow
})).RequireRateLimiting("Api");

app.MapControllers();

app.Run();
