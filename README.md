# Laravel Inertia E-Commerce

A modern, full-featured e-commerce application built with **Laravel 12**, **Inertia.js v2**, **Vue 3**, and **Tailwind CSS v4**.

## 🚀 Features

### ✅ Implemented Features

**Product Catalog Management**
- **Product Management**: Comprehensive CRUD for products, including variant management (simple, variable, bundle types).
- **Category Management**: Organize products into hierarchical categories.
- **Attribute Management**: Define custom attributes (color, size, material, etc.) for products.
- **Attribute Families**: Group attributes into reusable families for different product types.

**Shop & Outlet Management**
- **Shop/Outlet Management**: Manage physical or digital store locations and details.

**User & Access Control**
- **User Management**: Administer user accounts.
- **Role & Permission Management**: Granular access control using Spatie Permissions. Create roles and assign specific permissions via a visual interface.

**System Administration**
- **Dynamic Menu Builder**: Create and reorder sidebar menus and nested sub-menus directly from the dashboard.
- **Activity Logging**: Track detailed user activities and system changes.
- **Localization & Currency**:
    - **Multi-Locale**: Comprehensive support for multiple languages across the platform.
    - **Multi-Currency**: Handle pricing and transactions in various currencies.

---

### 🚧 Work In Progress (WIP)

**Frontend & Customer Experience**
- [ ] **Homepage**: Modern, responsive landing page.
- [ ] **Shopping Cart**: Real-time cart management.
- [ ] **Checkout Flow**: Secure and streamlined checkout process.
- [ ] **Voucher Management**: Discount codes and promotional campaigns.

**Integrations**
- [ ] **Payment Gateways**:
    - Midtrans
    - Tripay
    - Xendit
    - Doku
- [ ] **Shipping**:
    - Raja Ongkir Integration for real-time shipping rates.

---

## 📦 Tech Stack

- **Backend**: PHP 8.4+, Laravel 12
- **Frontend**: Vue 3, Inertia.js v2
- **Styling**: Tailwind CSS v4, Shadcn-vue
- **Database**: mysql
- **Tools**: Laravel Wayfinder, Pest PHP, Pint

## 🛠 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM

### Steps

1.  **Clone the Repository**

    ```bash
    git clone <repository-url>
    cd <project-directory>
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Database Setup**

    Configure your `.env` with your database credentials, then run:

    ```bash
    php artisan migrate --seed
    ```

5.  **Run Development Server**

    ```bash
    php artisan serve
    npm run dev
    ```

    Access the application at `http://localhost:8000`.
