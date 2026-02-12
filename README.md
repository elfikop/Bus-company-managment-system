Bus Company Management System

A comprehensive web-based management system designed for a transport company to handle coach reservations, fleet management, and employee scheduling. This project was developed as a full-stack application using a three-tier architecture.
Features

    Role-Based Access Control (RBAC): Dedicated interfaces for Administrators (Managers), Employees (Drivers), and Clients.

    Reservation Lifecycle: Automated workflow from initial inquiry to manager valuation and final client acceptance.

    Resource Optimization: An algorithm that automatically suggests the most economical vehicle by matching the smallest available bus to the required passenger capacity.

    Fleet and Staff Management: Full CRUD operations for managing vehicles and employee records with referential integrity checks.

    Driver Schedule: Dynamic view for employees to track their assigned routes and work schedule.

Tech Stack

    Backend: PHP (Object-Oriented).

    Database: MySQL (InnoDB) utilizing the PDO interface for secure data handling.

    Frontend: HTML5 and CSS3.

    Testing: PHPUnit for unit and logic testing.

    Architecture: Pattern following MVC (Model-View-Controller) principles with class generalization.

Security Measures

    SQL Injection Prevention: Consistent use of prepared statements and parameterized queries through PDO.

    Password Security: Passwords are never stored in plain text; they are hashed using the bcrypt algorithm.

    Session Management: Secure session-based authorization at the script level to prevent unauthorized resource access.

    Data Integrity: Implementation of foreign keys and try-catch blocks to prevent orphaned records and maintain logical consistency.

Installation

    Clone the repository to your local server directory.

    Create a MySQL database named "system".

    Import the SQL schema provided in the project files.

    Configure database connection parameters in projekt/classes/dbh.classes.php.

    Install dependencies using Composer to generate the vendor folder (required for testing): composer install
