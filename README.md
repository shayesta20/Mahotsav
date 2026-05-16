# Mahotsav

A simple, local PHP + MySQL event registration application intended for XAMPP/WAMP development.

## Overview

Mahotsav is a lightweight event and registration system built with plain PHP and MySQL. It provides basic user registration, login, event listing, and registration management for small local deployments or learning purposes.

## Features

- User registration and login
- Event creation and listing
- Registration management (view/delete)
- Simple file-based project that runs on XAMPP/WAMP

## Requirements

- PHP 7.0+ (or the version provided by your XAMPP)
- MySQL / MariaDB
- XAMPP (recommended for local development)

## Quick Setup

1. Copy the project folder to your web root (for XAMPP):

```powershell
C:\xampp\htdocs\mahotsav
```

2. Start Apache and MySQL from the XAMPP Control Panel.
3. Create a database (example name: `mahotsav`).
4. If you have a SQL dump, import it. If not, create the required tables manually.
5. Update database credentials in `config.php` (DB_HOST, DB_USER, DB_PASS, DB_NAME).
6. Open the app in your browser: `http://localhost/mahotsav`

## Configuration

Edit `config.php` to match your local database credentials. Example:

```php
<?php
// config.php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mahotsav');
?>
```

Note: `config.php` is in `.gitignore` to avoid committing credentials.

