# 🛒 Daily Needs – Marketplace Application

**Daily Needs** is a Laravel 8-based marketplace platform designed to support everyday shopping needs. It features three main user roles: **Customer**, **Admin**, and **Courier**, each with their own functionalities and access control. The application prioritizes clean architecture, performance optimization, and secure user experiences.

---

## 👥 User Roles & Core Features

### 👤 Customer
- Sign up and log in securely
- Browse and filter products by category
- Search products by name or keyword
- View product details
- Add products to cart
- Checkout products and complete orders
- Top up balance
- View purchase history
- Edit profile

### 🛠️ Admin
- Sign up and log in securely
- Add, edit, and manage product listings
- Filter and search products
- Manage and process customer orders
- View transaction history
- Edit admin profile

### 🚚 Courier
- Sign up and log in securely
- View assigned delivery orders
- Update delivery status
- Edit courier profile

---

## ⚙️ Technical Highlights

- **Framework**: Laravel 8
- **Authentication & Authorization**:
  - Custom authentication guards for each user role (Customer, Admin, Courier)
  - Role-based middleware to prevent unauthorized page access
- **Database Optimization**:
  - Implements **Eager Loading** and **Lazy Eager Loading** to avoid N+1 query problems
- **Security**:   
  - CSRF protection
  - SQL Injection protection
  - Middleware access restriction per user role
- **User Experience**:
  - Seamless navigation and filtering
  - Responsive error handling
  - Session management

---

## 🚀 How to Run Locally

### 1. Clone the Repository

- git clone https://github.com/your-username/daily-needs.git
- cd daily-needs

### 2. Install Dependencies

- composer install

### 3. Set Up Environment

- cp .env.example .env
- php artisan key:generate

Edit `.env` and configure your database credentials.

### 4. Run Migrations and Seeders

- php artisan migrate --seed

### 5. Start Server

- php artisan serve

Visit `http://localhost:8000` to open the application.

---

## 📌 Notes

- Product filtering supports both category and search keyword
- The application handles access control with Laravel middleware to ensure that each user role can only access their respective features
- Purchase and transaction data are handled efficiently using Laravel relationships and optimized queries