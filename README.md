# Symfony - SwiggyFood Recommendations

![SwiggyFood Logo](public/uploads/swiggy_logo.png)

## 📋 Project Overview

SwiggyFood Recommendations is a comprehensive web application built with Symfony 5 that helps users discover and share culinary recommendations. The platform allows administrators to manage food-related recommendations and enables users to view, search, and leave reviews on these recommendations.

## ✨ Key Features

### Frontend Features
- **Interactive Recommendation Gallery**: Browse through a visually appealing list of culinary recommendations
- **Detailed Recommendation Pages**: View comprehensive details about each recommendation
- **Video Playback**: Watch instructional videos directly on the platform with a custom modal player
- **Review System**: Leave ratings and comments on recommendations
- **Category Filtering**: Filter recommendations by category
- **Responsive Design**: Optimized for all device sizes
- **Search Functionality**: Find recommendations by keywords, techniques, or ingredients

### Backend Features
- **Admin Dashboard**: Manage all recommendations through a comprehensive control panel
- **Recommendation Management**: Add, edit, delete, and categorize recommendations
- **Video Upload**: Upload and manage video content for each recommendation
- **User Management**: Control user access and permissions
- **Statistics & Reporting**: Track recommendation popularity and engagement metrics
- **Filtering System**: Advanced filtering and sorting options using Lexik Form Filter Bundle
- **Export Capabilities**: Export data to CSV and Excel formats

## 🛠️ Technologies Used

- **Framework**: Symfony 5
- **Database**: MySQL
- **Frontend**: 
  - Twig Templates
  - Bootstrap 5
  - jQuery
  - Font Awesome
- **Libraries & Bundles**:
  - KnpPaginatorBundle for pagination
  - LexikFormFilterBundle for advanced filtering
  - PhpSpreadsheet for export functionality
- **Server**: Apache/Nginx with PHP 7.4+

## 🔧 Prerequisites

Before installing the project, make sure you have the following installed:

- **PHP 7.4 or higher**
- **Composer**
- **MySQL 5.7 or higher**
- **Symfony CLI** (optional, but recommended for development)
- **npm or yarn** (for managing frontend assets)
- **Git**

To check if you have the correct PHP version and extensions:

```bash
php -v
php -m
```

Required PHP extensions:
- intl
- pdo_mysql
- mbstring
- xml
- gd
- zip

## 🚀 Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/skanderbenali/SwiggyFood-Recommendations.git
   cd SwiggyFood-Recommendations
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install  # or yarn install
   ```

3. **Configure the database**:
   - Copy `.env.example` to `.env`
   - Configure your database connection in `.env`
   ```
   DATABASE_URL="mysql://username:password@127.0.0.1:3306/recommendations_db?serverVersion=8.0"
   ```

4. **Create the database and tables**:
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:schema:create
   # OR if you have migrations
   php bin/console doctrine:migrations:migrate
   ```

5. **Load sample data (optional)**:
   ```bash
   php bin/console doctrine:fixtures:load
   ```

6. **Build the frontend assets**:
   ```bash
   npm run build  # or yarn build
   ```

7. **Set up file uploads directory**:
   ```bash
   mkdir -p public/uploads/conseil
   # For Windows
   icacls public/uploads /grant Everyone:F /T
   # For Linux/Mac
   chmod -R 777 public/uploads
   ```

## 🏃‍♂️ Running the Application

### Development Environment

1. **Start the Symfony development server**:
   ```bash
   symfony server:start
   ```

2. **Watch for asset changes (in a separate terminal)**:
   ```bash
   npm run watch  # or yarn watch
   ```

3. **Access the application**:
   - Frontend: `http://localhost:8000/conseilsFront`
   - Backend: `http://localhost:8000/conseils`
   - Default admin credentials (if using fixtures):
     - Username: `admin@swiggy.com`
     - Password: `password123`

### Production Environment

1. **Set the environment to production in .env**:
   ```
   APP_ENV=prod
   ```

2. **Clear and warm up the cache**:
   ```bash
   php bin/console cache:clear --env=prod
   php bin/console cache:warmup --env=prod
   ```

3. **Optimize Composer's autoloader**:
   ```bash
   composer dump-autoload --optimize --no-dev --classmap-authoritative
   ```

4. **Build assets for production**:
   ```bash
   npm run build  # or yarn build
   ```

5. **Configure your web server (Apache or Nginx)**:
   
   **Apache Configuration Example**:
   ```apache
   <VirtualHost *:80>
       ServerName recommendations.example.com
       DocumentRoot /path/to/SwiggyFood-Recommendations/public
       
       <Directory /path/to/SwiggyFood-Recommendations/public>
           AllowOverride All
           Order Allow,Deny
           Allow from All
           Require all granted
           
           FallbackResource /index.php
       </Directory>
       
       ErrorLog /var/log/apache2/recommendations_error.log
       CustomLog /var/log/apache2/recommendations_access.log combined
   </VirtualHost>
   ```
   
   **Nginx Configuration Example**:
   ```nginx
   server {
       listen 80;
       server_name recommendations.example.com;
       root /path/to/SwiggyFood-Recommendations/public;
       
       location / {
           try_files $uri /index.php$is_args$args;
       }
       
       location ~ ^/index\.php(/|$) {
           fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
           fastcgi_split_path_info ^(.+\.php)(/.*)$;
           include fastcgi_params;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           fastcgi_param DOCUMENT_ROOT $realpath_root;
           internal;
       }
       
       location ~ \.php$ {
           return 404;
       }
       
       error_log /var/log/nginx/recommendations_error.log;
       access_log /var/log/nginx/recommendations_access.log;
   }
   ```

## 🐛 Troubleshooting Common Issues

### Database Connection Issues
- Ensure your MySQL server is running
- Check the credentials in your `.env` file
- Verify database connection:
  ```bash
  php bin/console doctrine:schema:validate
  ```

### Permission Problems
- Ensure uploads directory has proper permissions
- Check that the web server user has write access to the cache and logs directories
- On Linux/Mac systems:
  ```bash
  chmod -R 777 var/cache var/log public/uploads
  ```

### Missing Assets
- Make sure you've built the assets:
  ```bash
  npm run build  # or yarn build
  ```
- Clear the cache:
  ```bash
  php bin/console cache:clear
  ```

## 🔄 Updating the Application

To update your local copy of the application:

1. **Pull the latest changes**:
   ```bash
   git pull origin main
   ```

2. **Update dependencies**:
   ```bash
   composer install
   npm install  # or yarn install
   ```

3. **Update the database schema**:
   ```bash
   php bin/console doctrine:schema:update --force
   # OR if you have migrations
   php bin/console doctrine:migrations:migrate
   ```

4. **Clear the cache**:
   ```bash
   php bin/console cache:clear
   ```

## 🗂️ Project Structure

```
SwiggyFood-Recommendations/
├── config/                 # Configuration files
├── public/                 # Public web files
│   ├── css/                # CSS files
│   ├── js/                 # JavaScript files
│   ├── Front/              # Frontend assets
│   ├── Back/               # Backend assets
│   └── uploads/            # User uploads
├── src/
│   ├── Controller/         # Controllers
│   ├── Entity/             # Entity classes
│   ├── Form/               # Form types
│   ├── Repository/         # Repository classes
│   └── ...
├── templates/
│   ├── Front/              # Frontend templates
│   ├── Back/               # Backend templates
│   └── ...
└── ...
```

## 🔑 Important Code Conventions

- **Variable Naming**: 
  - Recommendations data is passed to templates as `c` (not `conseils`)
  - Forms are passed as `fc` (not `conseilType`)
- **File Structure**:
  - Frontend templates in `templates/Front/`
  - Backend templates in `templates/Back/conseil/`
- **Key Routes**:
  - List all recommendations: `/conseilsFront`
  - View recommendation details: `/conseilsFront/{idc}`
  - Admin dashboard: `/conseils`
  - Add recommendation: `/conseil/new`

## 📝 Usage Guidelines

### Adding a Recommendation
1. Log in to the admin area
2. Navigate to "Add Recommendation"
3. Fill in the required fields:
   - Recommendation name
   - Description
   - Category
   - Product relation
   - Upload a video file
4. Submit the form

### Filtering Recommendations
1. Go to the recommendations list
2. Use the category filter on the left sidebar
3. Alternatively, use the search box for keyword searching

### Reviewing a Recommendation
1. Navigate to a recommendation's details page
2. Click on the "Add Review" tab
3. Rate the recommendation (1-5 stars)
4. Add a title and comments
5. Submit your review

## 👥 Contributors

- [Your Name] - Project Lead
- [Other Contributors]

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgements

- SwiggyFood for branding elements and Logo used as an example only
- Bootstrap for frontend components
- Symfony community for excellent documentation and resources