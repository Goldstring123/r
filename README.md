# Real Estate Agency Portal (PHP + MySQL)

A role-based web application where real estate agents manage property listings and buyers or renters browse listings and send inquiries. Built for CIS344 (Database Design) at Lehman College, Spring 2026.

## Features
- **Secure registration and login:** passwords hashed with `password_hash()` and checked with `password_verify()`; user data kept in PHP sessions.
- **Role-based access control:** `requireRole()` limits pages by role.
  - **Agent:** add property listings.
  - **Buyer / Renter:** browse listings, view details, and submit inquiries to the listing agent.
- **Property listings:** list view and detail view, joined to the listing agent.
- **Relational database:** five tables (Users, Properties, Inquiries, Favorites, Transactions) linked by foreign keys.
- **Prepared statements (PDO)** for all database queries.
- **Security hardening:** CSRF tokens on every form, session ID regeneration at login, input validation, agent access code, and generic error messages (real errors go to the server log).

## Tech stack
PHP 8, MySQL/MariaDB, PDO, HTML/CSS, XAMPP for local development.

## Project structure
```
config/      database settings and session start
classes/     Database (PDO connection) and RealEstateDatabase (queries)
includes/    shared header/footer and auth helpers (requireLogin, requireRole)
sql/         schema.sql (database setup)
assets/      CSS
*.php        pages: index, register, login, dashboard, properties, property_details,
             add_property, submit_inquiry, logout
```

## Run it locally
1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy this folder into `C:\xampp\htdocs\real-estate-agency-portal`.
3. Open phpMyAdmin (`http://localhost/phpmyadmin`), go to **Import**, and import `sql/schema.sql`.
4. Check `config/config.php` (defaults: host `localhost`, user `root`, empty password, database `real_estate_portal_db`).
5. Visit `http://localhost/real-estate-agency-portal/` and register a buyer. To register an agent, enter the access code from `config/config.php` (default `AGENCY2026`).

## Screenshots
| Login | Listings | Agent dashboard |
|-------|----------|-----------------|
| ![Login](screenshots/login.png) | ![Listings](screenshots/properties.png) | ![Dashboard](screenshots/dashboard.png) |

## Challenges and what I learned
- **Port conflicts in XAMPP:** Apache and MySQL would not start because other programs held ports 80 and 3306. I found the processes in Task Manager and stopped them (and stopped an old MySQL Windows service with `net stop MySQL`).
- **Foreign key order:** MySQL rejected a property with an `agentId` that did not exist, which taught me to insert parent rows (Users) before child rows (Properties).
- **Browser caching:** PHP changes did not show up until a hard refresh (Ctrl+F5).

## Known limitations and next steps
- Agent sign-up uses a shared access code (`AGENT_ACCESS_CODE` in `config/config.php`); a real system would use admin approval.
- Agents can add listings but not yet edit or delete them.
- Favorites and Transactions tables exist, but the pages that use them are not built yet.

## Documentation
The full write-up is in [`CIS344_Finale_Report.pdf`](CIS344_Finale_Report.pdf).
