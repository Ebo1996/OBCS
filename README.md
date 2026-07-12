# OBCS — Online Birth Certificate System

A full-stack web application for managing birth certificate registrations, approvals, and issuance for Ifaa Bulaa Kebele Administration, Jimma, Oromia, Ethiopia.

🌐 **Live Demo:** [http://obcskebele.infinityfreeapp.com/](http://obcskebele.infinityfreeapp.com/)

---

## Features

- **Public Homepage** — Service overview, announcements, and community feedback
- **User Dashboard** — Apply for birth certificates, track application status, make payments
- **Admin Dashboard** — Manage users, announcements, hospitals, and officers
- **Hospital Dashboard** — Submit birth records, manage records
- **Kebele Staff Dashboard** — Manage kebele IDs, review applications
- **Officer Dashboard** — Review and approve/reject applications, send messages
- **Email Notifications** — Automated emails via PHPMailer on application approval
- **QR Code** — Generated on issued certificates for verification
- **Payment System** — Upload payment screenshot for certificate fee
- **Role-based Login** — Separate login for Admin, Hospital, User, Kebele Staff, Officer


---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML, CSS, Bootstrap 5, JavaScript |
| Backend | PHP (MySQLi) |
| Database | MySQL |
| Email | PHPMailer (Gmail SMTP) |
| QR Code | phpqrcode library |
| Hosting | InfinityFree |

---

## Project Structure

```
OBCS/
├── ADMIN/              # Admin dashboard pages
├── assets/             # Images and uploaded files
├── hospitaldashboard/  # Hospital dashboard pages
├── image/              # UI icons
├── images/             # Static images
├── kebeledashboard/    # Kebele staff dashboard pages
├── officer/            # Officer dashboard pages
├── phpqrcode/          # QR code generation library
├── public/             # Login and signup pages
├── setup/              # Database connection & SQL files
│   ├── dbconnection.php
│   └── missing_tables.sql
├── userdashboard/      # User dashboard pages
├── vendor/             # PHPMailer and other dependencies
└── index.php           # Main homepage
```

---

## Database Setup

1. Create a MySQL database named `birth_certificate_database`
2. Import the main schema (tables: users, applications, certificates, hospitals, birth_records, messages, announcements, payments, kebele_ids, kebele_officers, application_support, account_support)
3. Run `setup/missing_tables.sql` to create the `feedback` and `applications_support` tables
4. Run this to add the missing column:
```sql
ALTER TABLE users ADD COLUMN current_address VARCHAR(255) AFTER last_name;
```

---

## Local Setup (XAMPP)

1. Clone the repository:
```bash
git clone https://github.com/Ebo1996/OBCS.git
```
2. Move the folder to `C:\xampp\htdocs\OBCS`
3. Start **Apache** and **MySQL** in XAMPP Control Panel
4. Import your database in phpMyAdmin
5. Update `setup/dbconnection.php`:
```php
$hostName = "localhost";
$username = "root";
$password = "";
$database = "birth_certificate_database";
```
6. Visit `http://localhost/OBCS/`

---

## Login Roles

| Role | Path |
|------|------|
| Admin | `/public/login_new/addminlogin.php` |
| Hospital | `/public/login_new/hopsitalogin.php` |
| User | `/public/login_new/userlogin.php` |
| Kebele Staff | `/public/login_new/kebelelogin.php` |
| Officer | `/public/login_new/officerlogin.php` |

---

## Deployment

Currently deployed on **InfinityFree** free hosting.

- **Live URL:** [http://obcskebele.infinityfreeapp.com/](http://obcskebele.infinityfreeapp.com/)
- **Database Host:** sql305.infinityfree.com
- **Files uploaded via:** FileZilla FTP

---

## Contributing

Pull requests are welcome. For major changes, please open an issue first.

---

## License

This project was developed for Ifaa Bulaa Kebele Administration, Jimma, Oromia, Ethiopia.
