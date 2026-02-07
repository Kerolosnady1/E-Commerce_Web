# ERP & E-Commerce System (Laravel)

A comprehensive Enterprise Resource Planning (ERP) and E-Commerce management system built with Laravel 12. This system is designed to manage various aspects of a business including inventory, sales, accounting, human resources, and detailed reporting.

## 🚀 Key Features

### 📦 Inventory & Products
- **Product Management**: Create, edit, and manage products with support for barcodes, categories, and multiple pricing tiers.
- **Stock Control**: Real-time tracking of stock levels, alerts for low stock, and warehouse management.
- **Print Templates**: Customizable templates for printing labels and invoices.

### 💰 Sales & Invoicing
- **Point of Sale (POS)**: Fast and efficient interface for processing sales.
- **Invoicing**: Generate professional invoices (PDF support), manage tax rates, and track payments.
- **Quotations**: Create and manage price quotes for customers.

### 📊 Accounting & Finance
- **Double-Entry Ledger**: Full accounting system with journals, assets, liabilities, equity, revenue, and expenses.
- **Chart of Accounts**: Customizable COA to fit business needs.
- **Financial Reports**: Balance Sheet, Profit & Loss, and Tax Reports.

### 🔐 Security & User Management
- **Role-Based Access Control (RBAC)**: Granular permissions for users (Admin, Manager, Cashier, etc.).
- **Two-Factor Authentication (2FA)**: Enhanced security with Google Authenticator support.
- **Security Logs**: Detailed audit trails for login activities and sensitive actions.
- **Password Policy**: Enforceable password strength rules.

### ⚙️ System Settings
- **Dynamic Subscription Plans**: Database-driven subscription management for SaaS implementation.
- **Localization**: Support for Arabic (RTL) and English, with timezone and currency configurations.
- **Company Settings**: Manage company profile, logos, and tax identification numbers.

## 🛠️ Requirements

- **PHP**: >= 8.2
- **Composer**: Latest version
- **Node.js**: >= 18.x
- **Database**: SQLite (Default) / MySQL / PostgreSQL

## 📥 Installation & Setup

Follow these steps to set up the project locally:

### 1. Clone the Repository
```bash
git clone <repository_url>
cd ecommerce_laravel
```

### 2. Install Dependencies
Install PHP and JavaScript dependencies:
```bash
composer install
npm install
```

### 3. Environment Setup
Copy the example environment file and generate the application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
The project is configured to use SQLite by default. Creating the database file:
- **Windows**:
  ```powershell
  New-Item -ItemType File database/database.sqlite
  ```
- **Linux/Mac**:
  ```bash
  touch database/database.sqlite
  ```

Run migrations and seed the database with initial data (Admin user, Plans, Roles, Permissions):
```bash
php artisan migrate --seed --class=DatabaseSeeder
```
*Note: This will also verify the installation of Dynamic Plans.*

### 5. Compile Assets
Build the frontend assets (Tailwind CSS, JS):
```bash
npm run build
```

### 6. Run the Application
Start the local development server:
```bash
php artisan serve
```
Access the application at: [http://localhost:8000](http://localhost:8000)

## 👤 Default Credentials

After seeding the database, you can log in with:

- **Email**: `admin@example.com` (or `test@example.com` depending on seeder)
- **Password**: `password`

## 📁 Project Structure

- `app/Models`: Eloquent models (Product, SaleInvoice, User, etc.).
- `app/Http/Controllers`: Business logic and request handling.
- `database/migrations`: Database schema definitions.
- `resources/views`: Blade templates for the UI.
- `routes/web.php`: Application routes definition.

## 🤝 Contributing

1. Fork the repository.
2. Create a new feature branch (`git checkout -b feature/amazing-feature`).
3. Commit your changes (`git commit -m 'Add some amazing feature'`).
4. Push to the branch (`git push origin feature/amazing-feature`).
5. Open a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
