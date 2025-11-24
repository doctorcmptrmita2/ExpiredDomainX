# DataForSEO API Entegrasyonu

## Yapılandırma

DataForSEO API bilgileri `.env` dosyasında yapılandırılmıştır:

```env
DATAFORSEO_API_URL=https://api.dataforseo.com/v3
DATAFORSEO_API_LOGIN=info@whadvisor.io
DATAFORSEO_API_PASSWORD=32ab7f35adb1dde1
```

## Kullanım

### 1. Gerçek DataForSEO API'sini Kullanmak

`app/Providers/DataForSeoServiceProvider.php` dosyasında binding'i değiştirin:

```php
$this->app->bind(
    DataForSeoClientInterface::class,
    HttpDataForSeoClient::class  // FakeDataForSeoClient yerine
);
```

### 2. Artisan Komutu ile Domain Çekme

Belirli bir domain için DataForSEO'dan veri çekmek:

```bash
php artisan domain:fetch example.com
```

### 3. Programatik Kullanım

```php
use App\Services\Domain\DomainIngestionService;

$ingestionService = app(DomainIngestionService::class);
$domain = $ingestionService->fetchDomainFromDataForSeo('example.com');
```

## DataForSEO API Endpoint'leri

### WHOIS Data
- Endpoint: `/v3/domain_analytics/whois/live`
- Method: POST
- Authentication: Basic Auth (login:password Base64 encoded)

### Backlinks Data
- Endpoint: `/v3/backlinks/summary/live`
- Method: POST
- Authentication: Basic Auth

## Notlar

- DataForSEO API'si expired domain listesi sağlamaz
- Domain adlarını manuel olarak sağlamanız gerekir
- API rate limit'leri için DataForSEO dokümantasyonunu kontrol edin
- Her API çağrısı için ücretlendirme yapılabilir

