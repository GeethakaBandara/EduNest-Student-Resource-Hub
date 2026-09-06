# EduNest - Student Resource Hub

EduNest is a resource-sharing platform for university students. It allows students to upload, browse, and download study materials like lecture notes, past papers, and assignments. 

This project was built for the ICT 1209 - Web Technologies Mini Project.

## Technologies Used
- **Backend:** PHP 8 (Procedural PHP)
- **Database:** MySQL via PDO (with prepared statements)
- **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JavaScript
- **Version Control:** Git & GitHub

## How to Run Locally with XAMPP

1. **Install XAMPP**: If you haven't already, download and install [XAMPP](https://www.apachefriends.org/index.html).
2. **Start Servers**: Open the XAMPP Control Panel and start the **Apache** and **MySQL** modules.
3. **Place the Project**: Move this entire project folder (`Website` or `EduNest-Student-Resource-Hub`) into your XAMPP `htdocs` directory:
   - **Windows**: `C:\xampp\htdocs\`
   - **Mac**: `/Applications/XAMPP/htdocs/`
4. **Setup the Database**:
   - Open your browser and go to `http://localhost/phpmyadmin/`.
   - The database should be created automatically using the `database.sql` file provided. If not, create a new database named `student_resource_hub`.
   - Click on the `student_resource_hub` database, go to the **Import** tab, choose the `database.sql` file from this project folder, and click **Import** to create the tables.
5. **Run the Application**: 
   - Open your browser and navigate to `http://localhost/Website/` (replace `Website` with the exact name of your folder inside `htdocs`).
   - You can now register, login, upload resources, and browse materials!

## Features
- **User Authentication**: Secure registration and login using `password_hash()` (bcrypt).
- **File Uploads**: Authenticated users can upload study materials (PDF, DOCX, MP4) up to 50MB. Files are saved securely in the `uploads/` directory.
- **Resource Browsing**: Filter materials by category, study year, and semester, or search by keyword.
- **Dashboard**: Users can view their own uploaded resources.
- **Contact Form**: Users can submit messages which are stored in the database.
- **Security**: Prevented SQL Injection using PDO Prepared Statements. Prevented XSS by sanitizing all user inputs before displaying them.