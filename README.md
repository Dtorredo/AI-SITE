# Aurai Solutions - AI Services Website

This is the official website for Aurai Solutions, providing AI-powered business automation services.

## Features

- Modern, responsive design
- Contact form with email notifications
- Service showcase
- Pricing plans
- FAQ section
- Admin dashboard for form submissions

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer (for PHP dependencies)
- Node.js & NPM (for frontend assets)

## Installation

1. **Clone the repository**
   ```bash
   git clone [repository-url] aurai-site
   cd aurai-site

Install PHP dependencies

composer install

Install Node.js dependencies

npm install

Setup environment


Copy .env.example to .env

Update the .env file with your database and email settings

Generate an application key:
php -r "echo 'APP_KEY=base64:' . base64_encode(random_bytes(32));"

Add this to your .env file



Database setup


Create a new MySQL database

Import the database schema from database/schema.sql

Update the .env file with your database credentials



Configure web server


Point your web server's document root to the public directory

Ensure mod_rewrite is enabled (for Apache)

Set proper file permissions:
chmod -R 755 storage
chmod -R 755 bootstrap/cache



Email configuration


Update the email settings in .env

Test the email functionality using the contact form




Development


Frontend assets:

npm run dev    # Compile assets for development
npm run watch  # Watch for changes
npm run prod   # Compile assets for production

PHP Development Server:

php -S localhost:8000 -t public


Security


Keep your .env file secure and never commit it to version control

Use strong passwords for database and email accounts

Keep all dependencies up to date

Regularly backup your database


License

This project is proprietary software. All rights reserved.

Support

For support, please contact support@aurai.co.ke