# Nizek

A Laravel-based API for analyzing stock prices with Excel file upload functionality.

## Table of Contents
- [Installation](#installation)
- [Project Structure](#project-structure)
- [Design Patterns](#design-patterns)
- [Testing](#testing)
- [API Documentation](#api-documentation)
- [Postman Collection](#postman-collection)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/shahriyar3/nizek.git
cd nizek
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Configure your environment variables in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nizek
DB_USERNAME=nizek
DB_PASSWORD=nizek

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

4. Build and start Docker containers:
```bash
docker compose up -d
```

5. Install Composer dependencies:
```bash
docker compose exec app composer install
```

6. Generate application key:
```bash
docker compose exec app php artisan key:generate
```

7. Run migrations and seed the database:
```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

## Project Structure

```
├── app/
│   ├── Actions/           # Action classes for business logic
│   ├── Http/
│   │   ├── Controllers/   # API Controllers
│   │   └── Requests/      # Form Requests for validation
│   ├── Jobs/             # Queue jobs for async processing
│   ├── Models/           # Eloquent models
│   └── Services/         # Service classes
├── config/               # Configuration files
├── database/
│   ├── migrations/       # Database migrations
│   └── seeders/         # Database seeders
├── routes/
│   └── api.php          # API routes
├── storage/
│   └── stock_uploads/   # Uploaded Excel files
└── tests/
    ├── Feature/         # Feature tests
    └── Unit/            # Unit tests
```

## Design Patterns

The project implements several design patterns:

1. **Service Pattern**
   - `ExcelService` for handling Excel file operations
   - `StockPriceService` for business logic

2. **Action Pattern**
   - Action classes in `app/Actions` for single-responsibility business logic
   - Examples: `DispatchStockPriceProcessingJobAction`, `RetrieveStockPriceByDateAction`

3. **Job Pattern**
   - `ProcessStockPriceExcel` for handling async file processing

4. **Factory Pattern**
   - Used in tests for creating test data

## Testing

Run the test suite:

```bash
# Run all tests
docker compose exec app ./vendor/bin/phpunit

# Run specific test file
docker compose exec app ./vendor/bin/phpunit tests/Feature/StockPriceTest.php
```

## API Documentation

### Swagger Documentation
Access the API documentation at: `http://localhost:8000/api/documentation`

The Swagger UI provides:
- Detailed endpoint descriptions
- Request/response schemas
- Authentication requirements
- Example requests

## Postman Collection

A Postman collection is provided in `postman_collection.json` for testing the API endpoints.

### Using the Postman Collection

1. Import the collection:
   - Open Postman
   - Click "Import"
   - Select `postman_collection.json`

2. Set up environment:
   - Create a new environment
   - Add variable `base_url` with value `http://localhost:8000`

3. Available Endpoints:
   - **Upload Excel File**
     - Method: POST
     - URL: `{{base_url}}/api/stock-prices/upload`
     - Body: form-data
       - file: Excel file
       - company_id: Company ID

   - **Get Period Data**
     - Method: GET
     - URL: `{{base_url}}/api/stock-prices/periods`
     - Query: company_id

   - **Get Custom Period Data**
     - Method: GET
     - URL: `{{base_url}}/api/stock-prices/custom`
     - Query: company_id, start_date, end_date

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License.
