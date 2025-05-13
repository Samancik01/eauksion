# PHP E-Auction Project

This is a simple e-auction web application built using plain PHP and MySQL as part of a learning project. The main purpose of this project is to understand how online bidding systems work and to practice PHP backend development without using any frameworks.

## 📌 Project Purpose

This project was created for educational purposes. It demonstrates the core functionality of an auction system using only native PHP, HTML, CSS, and basic JavaScript.

## 🚀 Features

- User registration and login
- Create and manage auction items
- Place bids on active items
- Countdown timer for auctions
- Admin panel for managing users and items (optional)
- Simple and clean UI

## 🛠️ Technologies Used

- PHP (Vanilla / Core PHP)
- MySQL
- HTML5 & CSS3
- JavaScript (basic)
- Apache (recommended: XAMPP or Laragon for local testing)

## ⚙️ Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/yourusername/php-eauction.git
Import the database:

    Open your MySQL (phpMyAdmin or CLI)

    Import the auction.sql file into a database (e.g., eauction_db)

Update the database config:

    Open config/db.php and set your DB username, password, and DB name.

Run the project:

    Place the project folder inside htdocs (XAMPP) or www (Laragon)

    Navigate to http://localhost/php-eauction in your browser
    
📁 Folder Structure
/php-eauction
│
├── config/           # Database connection
├── includes/         # Common PHP functions
├── public/           # Main public pages (home, login, register)
├── admin/            # Admin panel (optional)
├── assets/           # CSS, JS, images
├── auction.sql       # Database file
└── README.md

🙋 Author

Samandar
Student of Applied Mathematics
Passionate about learning web development using PHP

📄 License

This project is open-source and free to use for educational purposes.
