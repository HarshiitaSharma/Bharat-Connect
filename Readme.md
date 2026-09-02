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

## ⚠️ Known Limitations 



## 👥 Team / Acknowledgment

Built as a submission for the **Skill India Web Development Competition**.
Project theme: *E-Governance — Digital Bridge Between Citizens and Local Government.*
