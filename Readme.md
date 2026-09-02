# Bharat Connect — E-Governance Citizen Services Portal

**Submitted for the Skill India Web Development Competition**

Bharat Connect is a digital platform that acts as a bridge between citizens and local government authorities. Citizens can register, log in, raise complaints or service requests (electricity, streetlights, water, certificates, pollution, road/pothole issues), and track their status. Government officials can log in as **Admin** to view and manage all incoming requests from a single dashboard.

---

## ✨ Features

- **Citizen registration & login** with hashed passwords (`password_hash` / `password_verify`)
- **Role-based access** — Citizen vs Admin/Official login
- **Service request form** with category tiles (Electric, Streetlight, Water, Certificate, Pollution, Road/Pothole)
- **Conditional fields** — Aadhar details are requested only for certificate-related applications
- **Live dashboard** showing submitted requests, with client-side filtering by category, status, and location search
- **Status statistics chart** (Chart.js doughnut chart) showing Submitted / In Progress / Resolved counts
- **Session-protected pages** — dashboard is inaccessible without login (`auth_check.php`)
- **Citizens see only their own requests; Admins see all requests**

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, Vanilla JavaScript (Fetch API) |
| Charts | Chart.js (via CDN) |
| Backend | PHP (procedural, session-based) |
| Data storage | PHP `$_SESSION` (in-memory, prototype only — see [Known Limitations](#-known-limitations-important)) |

---

## 📁 Actual Project Structure

```
Skillindia/
├── css/
│   └── style.css                 # Global styles (landing, login/register, forms)
├── js/
│   └── main.js                   # Shared front-end JS
├── php/
│   ├── includes/
│   │   ├── db_connect.php        # Empty stub — no real DB is connected (see below)
│   │   ├── header.php            # Empty stub — not currently used
│   │   ├── footer.php            # Empty stub — not currently used
│   │   └── auth_check.php        # Session guard, redirects to login if not authenticated
│   ├── api/
│   │   ├── get_request.php       # Empty stub (unused — see Known Issues)
│   │   └── update_status.php     # Empty stub — status updates not yet implemented
│   ├── get_requests.php          # Actual working endpoint that returns requests + stats as JSON
│   ├── login_process.php         # Handles login (citizen + hardcoded admin account)
│   ├── register_process.php      # Handles citizen registration
│   ├── submit_request.php        # Handles new complaint/request submission
│   └── logout.php                # Destroys session and redirects home
├── index.html                    # Landing page (contains embedded PHP — see Known Issues)
├── login.html                    # Login form
├── register.html                 # Registration form
├── dashboard.php                 # Protected dashboard (auth-checked, PHP-rendered)
├── dashboard.html                # Static dashboard variant
├── new_request.html              # Standalone new-request page
└── Readme.md                     # (this file)
```

---

## 🚀 Getting Started

1. Install a local PHP environment such as **XAMPP**, **WAMP**, or run PHP's built-in server.
2. Place the `Skillindia` folder inside your server's web root (e.g. `htdocs/`).
3. Start Apache/PHP, or from inside the project folder run:
   ```bash
   php -S localhost:8000
   ```
4. Open `http://localhost:8000/index.html` (or `/login.html`) in your browser.
5. Register a new citizen account, or log in as the built-in demo admin:

   | Role | Email | Password |
   |---|---|---|
   | Admin (demo) | `admin@example.com` | `admin123` |

> ⚠️ No database setup is required to run the prototype, because data is stored in the PHP session (see below) — this also means data does **not** persist between server restarts or different browser sessions.

---

## ⚠️ Known Limitations (Important)

Being transparent about the current state of the prototype helps reviewers/judges and future contributors:

1. **No real database.** `php/includes/db_connect.php` is present but empty. All users and requests are stored in `$_SESSION`, so:
   - Data is lost when the PHP session expires or the server restarts.
   - Two different browsers/devices will not see the same data.
   - This should be replaced with MySQL/MariaDB (e.g. via PDO) for a production version.
2. **Dashboard API path mismatch.** `dashboard.php` calls `fetch('php/api/get_requests.php')`, but the working endpoint actually lives at `php/get_requests.php` (the file at `php/api/get_request.php` — singular, no "s" — is an empty stub). Until this is fixed, the dashboard's live data fetch will fail; either move/rename `get_requests.php` into `php/api/` or update the fetch URL in `dashboard.php`.
3. **`header.php` and `footer.php` are empty** and not currently included anywhere — either wire them in for shared layout, or remove them to avoid confusion.
4. **Status updates aren't implemented yet.** `php/api/update_status.php` is an empty stub, so admins currently cannot change a request's status (Submitted → In Progress → Resolved) from the UI.
5. **Embedded PHP inside `.html` files.** `index.html` contains `<?php ... ?>` tags, which most servers will **not** execute inside a `.html` file (only `.php` files are parsed by default). Rename it to `index.php`, or configure the server to parse `.html` as PHP, for the login/logout conditional header to actually work.
6. **Aadhar numbers are collected in plain text** and stored in the session for certificate requests. For any real deployment involving government ID numbers, this data must be encrypted at rest, transmitted over HTTPS only, and access-restricted per data-protection best practices.
7. **No CSRF protection or input sanitization/validation** beyond basic "required" checks — recommended before any real-world use.

---

## 🔭 Suggested Future Improvements

- Migrate storage from PHP sessions to a proper database (MySQL/MariaDB) using PDO with prepared statements.
- Implement the `update_status.php` endpoint so admins can move requests through their lifecycle.
- Fix the `get_requests.php` path so the live dashboard fetch works end-to-end.
- Add email/SMS notifications when a request's status changes.
- Add file/photo upload support for complaints (e.g. photo of a pothole).
- Add pagination for the requests list as data grows.
- Add proper server-side validation, CSRF tokens, and rate-limiting on auth endpoints.

---

## 👥 Team / Acknowledgment

Built as a submission for the **Skill India Web Development Competition**.
Project theme: *E-Governance — Digital Bridge Between Citizens and Local Government.*

---

## 📄 License

This project was created for educational/competition purposes. Add a license of your choice (e.g. MIT) if you plan to share or extend it publicly.
