# Social Pulse — Portfolio Edition

A refreshed social-networking portfolio project built with PHP 8+, MySQL/MariaDB, HTML, and CSS. This edition replaces URL-based identity with server-side sessions, stores password hashes, uses prepared SQL statements, validates image uploads, and includes responsive layouts.

## Features

- Registration and sign-in using `password_hash()` / `password_verify()`
- Session-based authentication and logout
- Responsive feed showing your posts and posts from accounts you follow
- Image posts with captions
- Explore/search people
- Follow and unfollow accounts
- Profile pages, bio and avatar editing
- Likes and comments
- CSRF tokens on state-changing forms
- Prepared statements and output escaping

## Requirements

- XAMPP with PHP 8.1+ and MySQL/MariaDB
- PHP extensions `mysqli` and `fileinfo`
- A modern browser

## Install on XAMPP

1. Copy the `SocialPulse-Portfolio` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `socialpulse_db.sql`. The script creates and selects the `socialpulse_db` database and creates the required tables.
5. Open `connect.php`. The defaults are host `127.0.0.1`, user `root`, blank password, database `socialpulse_db`. Change these values if your local MySQL setup differs. For non-local deployments, configure the `SOCIALPULSE_DB_HOST`, `SOCIALPULSE_DB_USER`, `SOCIALPULSE_DB_PASS`, and `SOCIALPULSE_DB_NAME` environment variables instead of committing secrets.
6. Visit `http://localhost/SocialPulse-Portfolio/` and create an account.

## Uploads

Post images and avatars are saved under `uploads/`. The application accepts JPG, PNG, WEBP and GIF images up to 8 MB. Ensure the PHP process can write to the uploads folder. The included `.htaccess` blocks script execution in uploads on Apache; keep equivalent protections if deploying elsewhere.

## Database note

This edition uses a clean schema in `socialpulse_db.sql`. The original 2024 database used usernames as relationship keys and stored passwords in plain text. This version intentionally does **not** import those accounts or reuse their passwords. Register a new local test account. If you want to migrate the old sample data later, create a separate migration that maps old usernames to new numeric user IDs and forces password resets; never convert old plain-text passwords into production credentials.

## Portfolio / deployment checklist

Before deploying publicly:

- Use HTTPS and secure session cookies.
- Use a dedicated database user with only required privileges (not MySQL root).
- Add rate limiting and email verification to registration/login.
- Add moderation/reporting, privacy controls, and account deletion.
- Consider image resizing, EXIF stripping, and storage outside the web root.
- Add automated tests and a deployment-specific configuration.
- Review the license/rights for any demo images. Do not publish other people's images without permission.

## Project structure

- `app.php` — sessions, common helpers, CSRF, uploads, layout
- `connect.php` — database connection
- `index.php`, `register.php`, `logout.php` — authentication
- `feed.php`, `explore.php`, `profile.php`, `edit-profile.php` — main pages
- `post.php`, `like.php`, `follow.php` — post and relationship actions
- `assets/css/app.css` — responsive UI
- `assets/js/app.js` — small client-side enhancements
- `socialpulse_db.sql` — schema


## Interface themes

Social Pulse uses a soft lavender-and-plum color palette and includes a light/dark mode switch in the top navigation. The selected theme is stored in the current browser using local storage, so it is remembered on later visits on that same browser. No account or database setting is needed.
