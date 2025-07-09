# Beipoa - Modern eCommerce Website

A modern and responsive eCommerce website built with HTML, CSS, JavaScript, PHP, and MySQL. Features a dynamic product catalog, admin dashboard, and shopping cart functionality with prices displayed in Tanzanian Shilling (TSh).

## 🚀 Features

### Frontend Features
- **Modern Responsive Design** - Mobile-first design with Flexbox and Grid layouts
- **Animated Hero Section** - Background image slider with smooth animations
- **Dynamic Product Catalog** - Products loaded from MySQL database
- **Shop by Category** - 6 main categories: Mobile Phones, Computers, Electronics, TVs, Sports, Fashion
- **Featured Products** - Showcase of highlighted products
- **Most Loved Products** - Products based on ratings and sales
- **Shopping Cart** - Add to cart functionality with local storage
- **Wishlist** - Save favorite products
- **Search Functionality** - Product search with real-time results
- **Smooth Animations** - CSS animations and transitions throughout
- **Currency Formatting** - All prices displayed in Tanzanian Shilling (TSh 45,000)

### Backend Features
- **PHP Backend** - Secure server-side processing
- **MySQL Database** - Comprehensive database schema
- **Admin Dashboard** - Complete management system
- **User Authentication** - Secure login/registration system
- **Product Management** - CRUD operations for products
- **Category Management** - Organize products by categories
- **Order Management** - Track orders and payments
- **Analytics Dashboard** - Sales charts and statistics
- **Image Upload** - Product image management
- **Responsive Admin Panel** - Mobile-friendly admin interface

### Admin Dashboard Features
- **Dashboard Analytics** - Sales charts, revenue tracking, user statistics
- **Product Management** - Add, edit, delete products with image upload
- **Category Management** - Manage product categories
- **Order Management** - View and update order status
- **User Management** - Manage customer accounts
- **Review System** - Moderate product reviews
- **Revenue Tracking** - Real-time sales analytics

## 🛠️ Tech Stack

- **Frontend**: HTML5, CSS3 (Flexbox/Grid), JavaScript (ES6+)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Icons**: Font Awesome 6
- **Fonts**: Google Fonts (Poppins)
- **Charts**: Chart.js
- **Styling**: Modern CSS with animations and gradients

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- **Web Server** (Apache/Nginx)
- **PHP 7.4 or higher**
- **MySQL 5.7 or higher**
- **Web browser** (Chrome, Firefox, Safari, Edge)

## 🔧 Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/beipoa-ecommerce.git
cd beipoa-ecommerce
```

### 2. Database Setup

1. **Create Database**:
   ```sql
   CREATE DATABASE beipoa_ecommerce;
   ```

2. **Import Database Schema**:
   ```bash
   mysql -u your_username -p beipoa_ecommerce < database/schema.sql
   ```

3. **Configure Database Connection**:
   Edit `config/database.php` with your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'beipoa_ecommerce');
   ```

### 3. Web Server Setup

#### Using XAMPP/WAMP/MAMP:
1. Copy the project folder to your web server directory (`htdocs` for XAMPP)
2. Start Apache and MySQL services
3. Access the website at `http://localhost/beipoa-ecommerce`

#### Using Local Development Server:
```bash
php -S localhost:8000
```

### 4. Admin Access

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`

Access admin dashboard at: `http://localhost/beipoa-ecommerce/admin/`

### 5. File Permissions

Ensure proper file permissions for image uploads:
```bash
chmod 755 assets/images/
chmod 755 assets/images/products/
chmod 755 assets/images/categories/
```

## 📁 Project Structure

```
beipoa-ecommerce/
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   ├── js/
│   │   └── main.js            # Frontend JavaScript
│   └── images/
│       ├── products/          # Product images
│       ├── categories/        # Category images
│       └── hero/              # Hero section images
├── admin/
│   ├── index.php              # Admin dashboard
│   ├── login.php              # Admin login
│   ├── logout.php             # Admin logout
│   ├── products.php           # Product management
│   ├── categories.php         # Category management
│   ├── orders.php             # Order management
│   ├── users.php              # User management
│   └── assets/
│       └── css/
│           └── admin.css      # Admin panel styles
├── config/
│   └── database.php           # Database configuration
├── database/
│   └── schema.sql             # Database schema
├── includes/
│   ├── get_categories.php     # Categories API
│   ├── get_featured_products.php    # Featured products API
│   └── get_most_loved_products.php  # Most loved products API
├── index.html                 # Homepage
├── shop.php                   # Product listing
├── categories.php             # Category pages
├── cart.php                   # Shopping cart
├── login.php                  # User login
├── register.php               # User registration
└── contact.html               # Contact page
```

## 🎨 Customization

### Colors
The website uses a blue and white color scheme. To customize:

1. **Primary Color**: `#2563eb` (Blue)
2. **Secondary Color**: `#10b981` (Green)
3. **Accent Color**: `#ef4444` (Red)

Update colors in `assets/css/style.css` and `admin/assets/css/admin.css`.

### Currency
All prices are formatted in Tanzanian Shilling. To change currency:

1. Update the `formatTSh()` function in `config/database.php`
2. Modify JavaScript currency formatting in `assets/js/main.js`

### Categories
Default categories include:
- Mobile Phones
- Computers  
- Electronics
- TVs
- Sports
- Fashion

Add or modify categories through the admin dashboard.

## 🚀 Usage

### For Customers:
1. **Browse Products** - View products by category or featured items
2. **Search** - Use the search bar to find specific products
3. **Add to Cart** - Click "Add to Cart" on any product
4. **Wishlist** - Click the heart icon to save favorites
5. **Checkout** - Proceed to cart and complete purchase

### For Administrators:
1. **Login** - Access admin panel with credentials
2. **Dashboard** - View sales analytics and statistics
3. **Manage Products** - Add, edit, or remove products
4. **Process Orders** - Update order status and track payments
5. **User Management** - Manage customer accounts

## 🔒 Security Features

- **Password Hashing** - Bcrypt password encryption
- **SQL Injection Prevention** - Prepared statements
- **XSS Protection** - Input sanitization
- **Session Security** - Secure session management
- **CSRF Protection** - Form token validation
- **Admin Authentication** - Role-based access control

## 📱 Responsive Design

The website is fully responsive and optimized for:
- **Desktop** - Full features and layout
- **Tablet** - Adapted grid layouts
- **Mobile** - Mobile-first design with touch-friendly interface

## 🔧 API Endpoints

The website includes several API endpoints for dynamic content:

- `GET /includes/get_categories.php` - Fetch all categories
- `GET /includes/get_featured_products.php` - Fetch featured products
- `GET /includes/get_most_loved_products.php` - Fetch popular products

## 🐛 Troubleshooting

### Common Issues:

1. **Database Connection Error**:
   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Images Not Loading**:
   - Check file permissions on image directories
   - Verify image paths in database
   - Ensure image files exist

3. **Admin Panel Not Accessible**:
   - Verify admin user exists in database
   - Check session configuration
   - Clear browser cache

4. **JavaScript Errors**:
   - Check browser console for errors
   - Ensure all JavaScript files are loaded
   - Verify API endpoints are accessible

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Support

For support and questions:
- Email: support@beipoa.com
- GitHub Issues: [Create an issue](https://github.com/your-username/beipoa-ecommerce/issues)

## 🙏 Acknowledgments

- Font Awesome for icons
- Google Fonts for typography
- Chart.js for analytics charts
- PHP community for frameworks and libraries

---

**Happy Selling with Beipoa! 🛒**