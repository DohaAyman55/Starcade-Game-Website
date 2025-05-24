# Starcade Game Website

**Starcade** is a web-based game platform that provides a retro-modern arcade experience with full user interaction and administrative control. Users can log in, play games, view leaderboards, customize profiles, and compete for high scores, while admins manage platform content and monitor activity logs.

---

## 🚀 Features

### 🧑‍💻 User Features
- ✅ User Authentication (Sign up / Log in)
- 🎮 Access and play multiple browser-based games
- 🏆 Live Leaderboards for top scores
- ✨ Customizable user profiles
- 📈 Track personal high scores for each game

### 🛠️ Admin Features
- 🚫 Manage user restrictions (banning and activating accounts)
- ➕ Add / 🗑 Remove available games
- 📜 View activity logs of admins and users
- 🔧 Monitor platform content and maintain fair play
- Head admin can manage admin accounts add a new account

---

## 🛠 Tech Stack

- **Frontend**: HTML5, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL (via myphpadmin)
- **Server**: Apache (via XAMPP)
- **Auth**: Custom authentication system

---

## 📸 Screenshots
1. HomePage
![image](https://github.com/user-attachments/assets/d30a03d7-7bbd-40d5-ab88-ea72a68f0e83)

2. Games Page
![image](https://github.com/user-attachments/assets/282a1a81-4dec-4cd7-8fc3-19269b01e4c7)

3. LeaderBoard
![image](https://github.com/user-attachments/assets/01f5950e-d78d-4883-a16f-1eeaee5994fd)

4. Player Profile
![image](https://github.com/user-attachments/assets/0e31fa7f-87d2-46dd-9640-67246d3bf34a)

5. Admin Dashboard
![image](https://github.com/user-attachments/assets/9dc51f41-19a4-4d76-83fd-7835157c5fdf)


---

## ⚙️ Installation

Prerequisites
- XAMPP installed (includes Apache, PHP, MySQL)
- Git (optional, for cloning repo)
- A modern web browser


1. Clone the repository:
   
```bash
git clone https://github.com/yourusername/starcade.git
cd starcade
```

2. Move project files to your web server root
   
If using XAMPP, copy the entire starcade folder into the htdocs directory.
Usually found at:
C:\xampp\htdocs\starcade (Windows)
/opt/lampp/htdocs/starcade (Linux)

4. Create a MySQL database

Open phpMyAdmin by navigating to http://localhost/phpmyadmin
Click Databases and create a new database, e.g. gamesite
Use the SQL file Starcade Gamesite Database.sql to import the tables into the database.


