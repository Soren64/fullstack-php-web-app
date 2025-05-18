# Database-Driven Web Application

A full-stack web application developed as part of a team project for a university Database Systems course. The app is built using PHP and MySQL and was designed to demonstrate concepts in relational database design, SQL operations, input validation, and web-based data interaction.

## Entity-Relationship Diagram

![https://raw.githubusercontent.com/Soren64/fullstack-php-web-app/main/ER-Diagram.png](https://raw.githubusercontent.com/Soren64/fullstack-php-web-app/main/ER-Diagram%20.png))
[View full-size ER Diagram (PDF)](./ER-Diagram.pdf)

## Features

- Implements Tasks 1–8 from the course specification.
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
