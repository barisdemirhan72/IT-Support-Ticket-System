🎫 IT Support Ticket Management System

This project is a professional Laravel application designed to digitalize IT support processes, increase team efficiency, and manage the ticket lifecycle from end to end.

🚀 Key Features

Advanced RBAC (Role-Based Access Control): Specific permissions for Admin, Support Staff, and Standard User roles.

Ticket Lifecycle Management: Creation, assignment, status tracking (Open, In Progress, Resolved, Closed), and archiving.

Interactive Comment System: Internal private notes for agents and public responses for customers.

Event-Driven Notifications: Automated email alerts triggered by ticket updates and status changes.

Modern Interface: Fully responsive design built with Tailwind CSS and Alpine.js.

🛠️ Tech Stack

Backend: PHP 8.2+ / Laravel 10

Frontend: Tailwind CSS / Alpine.js

Database: PostgreSQL

Architecture: MVC (Model-View-Controller) + Event-Driven Architecture

Documentation: LaTeX

🔐 Test Login Credentials

You can use the following pre-configured accounts to quickly test the project:

Role

Email

Password

Administrator

admin@example.com

password

Support Staff

support1@example.com

password

Standard User

alice@example.com

password

📂 Project Structure & OOP Principles

This project is developed following Clean Code practices and Object-Oriented Programming (OOP) principles:

Encapsulation: Data is protected within Eloquent models and accessed via controlled methods.

Inheritance: Shared functionality is inherited from base Controller and Model classes.

Polymorphism: The comment system is built using a polymorphic structure, allowing it to be associated with different entities.

Middleware: Security and authorization layers are managed through Laravel Middleware.

📊 UML Diagrams

The following diagrams are ready to provide technical depth for the project:

✅ Database ERD: Table relationships, schemas, and keys.

✅ Class Diagram: Visual mapping of classes, methods, and properties.

✅ Sequence Diagrams: Logic flows for ticket creation, status updates, and commenting.

⚙️ Installation

Clone the repository:

git clone [https://github.com/barisdemirhan72/IT-Support-Ticket-System.git](https://github.com/barisdemirhan72/IT-Support-Ticket-System.git)


Navigate to the project directory:

cd IT-Support-Ticket-System


Install dependencies:

composer install
npm install && npm run dev


Configure the .env file and run migrations:

php artisan migrate --seed


Start the application:

php artisan serve


📜 License

This project is licensed under the MIT License.

Developed by barisdemirhan72