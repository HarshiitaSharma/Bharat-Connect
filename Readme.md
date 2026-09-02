<div align="center">

# Bharat Connect

**E-Governance Citizen Services Portal**

*A digital bridge between citizens and local government authorities*

Built for the **Skill India Web Development Competition**

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net/)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=flat&logo=chart.js&logoColor=white)](https://www.chartjs.org/)

</div>

---

## 📖 About

**Bharat Connect** lets citizens register complaints and request public services — electricity repairs, streetlight issues, water services, birth/death certificates, pollution complaints, and road/pothole repairs — directly through a simple web portal, and lets government officials track and manage those requests from one dashboard.

The goal: cut down the friction of reporting civic issues, and give local authorities a clearer, centralized view of what needs attention.

## ✨ Features

- 🔐 **Secure authentication** — citizen registration & login with hashed passwords
- 🧑‍💼 **Role-based access** — separate Citizen and Admin/Official login flows
- 📝 **Smart request form** — clickable service category tiles with conditional fields (e.g. Aadhar details appear only for certificate requests)
- 📊 **Live dashboard** — real-time request list with filtering by category, status, and location
- 📈 **Visual analytics** — status breakdown chart (Submitted / In Progress / Resolved) powered by Chart.js
- 🔒 **Protected routes** — dashboard and request pages are inaccessible without a valid session
- 👁️ **Scoped visibility** — citizens see only their own requests; admins see everything

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, Vanilla JavaScript (Fetch API) |
| Charts | Chart.js |
| Backend | PHP (session-based) |
| Data | PHP Sessions *(migrating to MySQL — see [Roadmap](#-roadmap))* |

## 📁 Project Structure

```
Bharat-Connect/
├── css/
│   └── style.css              # Global styles
├── js/
│   └── main.js                 # Shared front-end scripts
├── php/
│   ├── includes/
│   │   ├── db_connect.php      # Database connection
│   │   ├── header.php          # Shared header partial
│   │   ├── footer.php          # Shared footer partial
│   │   └── auth_check.php      # Session/auth guard
│   ├── api/
│   │   ├── get_requests.php    # Fetch requests + stats (JSON)
│   │   └── update_status.php   # Update a request's status
│   ├── login_process.php       # Handles login
│   ├── register_process.php    # Handles registration
│   ├── submit_request.php      # Handles new request submission
│   └── logout.php              # Ends session
├── index.html                  # Landing page
├── login.html                  # Login page
├── register.html                # Registration page
├── dashboard.php               # Citizen/Admin dashboard (protected)
└── new_request.html            # New request page
```

## 🚀 Getting Started

### Prerequisites
- [PHP](https://www.php.net/downloads) 7.4+
- A local server environment such as [XAMPP](https://www.apachefriends.org/) / [WAMP](https://www.wampserver.com/), or PHP's built-in server

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/<your-username>/bharat-connect.git
cd bharat-connect

# 2. Start a local PHP server
php -S localhost:8000
```

Then open **`http://localhost:8000/index.html`** in your browser.

### Demo Login

| Role | Email | Password |
|---|---|---|
| Admin | `admin@example.com` | `admin123` |

Citizens can create their own account via the **Register** page.

## 🗺️ Roadmap

- [ ] Move data storage from PHP sessions to MySQL/MariaDB
- [ ] Complete the status-update workflow for admins
- [ ] Add file/photo upload support for complaints
- [ ] Add email/SMS notifications on status changes
- [ ] Add CSRF protection and stronger input validation
- [ ] Encrypt sensitive fields (e.g. Aadhar numbers) at rest

## 🤝 Contributing

Contributions, issues, and feature requests are welcome. Feel free to open an issue or submit a pull request.

## 🙏 Acknowledgments

Built as a submission for the **Skill India Web Development Competition**, under the theme *E-Governance — Digital Bridge Between Citizens and Local Government*.

---

<div align="center">
Made with ❤️ for Digital India
</div>
