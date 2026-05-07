using ElaroApi.Data;
using Microsoft.EntityFrameworkCore;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.AddControllers();

// DbContext
builder.Services.AddDbContext<AppDbContext>(options =>
    options.UseSqlServer(builder.Configuration.GetConnectionString("DefaultConnection")));

// Swagger
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen(options =>
{
    options.SwaggerDoc("v1", new Microsoft.OpenApi.Models.OpenApiInfo
    {
        Title = "ElaroAPI",
        Version = "v1",
        Description = "Elaro e-ticaret platformu için ASP.NET Core Web API"
    });
});

// CORS
builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowAll", policy =>
    {
        policy.AllowAnyOrigin()
              .AllowAnyHeader()
              .AllowAnyMethod();
    });
});

var app = builder.Build();

// Global hata yakalama (opsiyonel ama önerilir)
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/error");
}

// Swagger her ortamda açýk (Azure dâhil)
app.UseSwagger();
app.UseSwaggerUI(c =>
{
    c.SwaggerEndpoint("/swagger/v1/swagger.json", "ElaroAPI v1");
    c.RoutePrefix = string.Empty; // root'tan açmak için (localhost:5000 ile direkt açýlýr)
});

// HTTPS ve CORS
app.UseHttpsRedirection();
app.UseCors("AllowAll");

app.MapControllers();

app.Run();
