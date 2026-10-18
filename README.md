# Social-Pulse

Social-Pulse is a PHP and MySQL social media web application built as a portfolio project. Users can create accounts, share image posts, interact with other users, and explore content.

## Features

- User registration, login, and logout
- User profiles with profile information and avatars
- Image posts with captions
- Likes and comments
- Follow and unfollow functionality
- Explore and user search
- Light and dark themes
- Database-backed storage for users and interactions
- Upload validation and protected pages for logged-out users

## Technologies

- PHP
- MySQL / MariaDB
- HTML
- CSS
- JavaScript
- XAMPP for local development

## Requirements

- XAMPP or another PHP and MySQL/MariaDB environment
- A web browser
- Git, if cloning the repository

## Installation

### 1. Download the project

Clone the repository:

```bash
git clone https://github.com/LithaMadlingozi03/Social-Pulse.git
```

Place the project inside your web server's document root. For XAMPP on Windows, this is normally:

```text
C:\xampp\htdocs\
```

### 2. Start the services

Open the XAMPP Control Panel and start **Apache** and **MySQL**.

### 3. Create the database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin/
```

Create a new database named:

```text
socialpulse_db
```

Select the database and import `database/schema.sql` first.

To populate a new, empty database with fictional demo users and content, import `database/demo_data.sql` after the schema.

**Important:** The demo data file is intended for a fresh database. Do not import it repeatedly or into a database that already contains users and posts, because its IDs may conflict with existing records.

### 4. Configure the database connection

Configure the application's database connection using your local MySQL settings. Keep local credentials in your local configuration file or environment variables.

Do not commit passwords, secret keys, private configuration files, or real user data to GitHub.

### 5. Open the application

If the project folder is named `Social-Pulse`, visit:

```text
http://localhost/Social-Pulse/
```

If you use a different folder name, replace `Social-Pulse` in the URL with that name.

## Demo account

For the prepared demo dataset, the example login is:

- **Username:** `maya.codes`
- **Password:** `DemoPass123!`

This account is available only after the demo data has been imported successfully. These are fictional portfolio-demo credentials.

## Project structure

```text
Social-Pulse/
├── database/
│   ├── schema.sql
│   └── demo_data.sql
├── uploads/
├── README.md
└── [application PHP, CSS, JavaScript and configuration files]
```

The exact project structure may vary depending on the files included in the repository.

## Security and privacy

- Use fictional data for demonstrations.
- Keep local configuration and database credentials out of version control.
- Do not publish real user information or private uploaded images.
- Use a secure, unique password for any deployed environment.
- Review file upload validation and access controls before deploying publicly.

## Status

Social-Pulse is a portfolio project intended to demonstrate PHP, database integration, user authentication, and social media application features.
