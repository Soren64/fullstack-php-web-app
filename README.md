# Database-Driven Web Application

A full-stack web application developed as part of a team project for a university Database Systems course. The app mimics a simplified version of the university's SIS (Student Information System). The app is built using PHP and MySQL and was designed to demonstrate concepts in relational database design, SQL operations, input validation, and web-based data interaction.

Check out the [Phase 3 Android App project here](https://github.com/Soren64/your-android-app-repo) for the mobile implementation of the database system.

## Entity-Relationship Diagram

![ER Diagram](https://raw.githubusercontent.com/Soren64/fullstack-php-web-app/main/ER-Diagram%20.png)
[ER Diagram PDF Download](https://raw.githubusercontent.com/Soren64/fullstack-php-web-app/main/ER-Diagram%20.pdf)
[ER Diagram PDF View](https://github.com/Soren64/fullstack-php-web-app/blob/main/ER-Diagram%20.pdf)

## Features

- Implements Tasks 1–8 from the course specification.
    -Allow student, professor, and administration login with relevant features matching role.
    -Able to view, add, update, and delete student records (e.g., name, major, GPA).
    -Students can sign up for courses (provided they have the matching prequisites).
    -Professors can assign TAs or graders for any course they are currently teaching (provided they meet the requirements- i.e. passed the course with a good grade).
    -Students/Professors can view course history, including grades.
- Designed and imported normalized relational schemas.
- Allows user interactions via web interface (CRUD operations, form handling).
- Validates user input to preserve data integrity.
- Follows a structured setup using XAMPP for local development.

## Technologies Used

- **Frontend**: HTML/CSS
- **Backend**: PHP
- **Database**: MySQL
- **Environment**: XAMPP (Apache + MySQL)
- **Version Control**: Git + GitHub

## Setup Instructions

1. Clone or download the repository.
2. Move the folder to your `xampp/htdocs/` directory.
3. Start **Apache** and **MySQL** via the XAMPP control panel.
4. Open your browser and go to [localhost/phpmyadmin](http://localhost/phpmyadmin).
5. Create a new database named `DB2`.
6. Click on the new `DB2` database, go to the **Import** tab.
7. Choose the `DB2-tables.sql` file and click **Import**.
8. In your browser, navigate to: [http://localhost/db2](http://localhost/db2)

## Team

- **Lucca Nelson**
- **Nick Matsuda** (this repository)
- **Jack Fallon**

## License

This project was developed for educational purposes and is not licensed for production use.
