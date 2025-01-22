# Ecommerce Application

## Overview

This **Ecommerce Application** is a fully developed solution built with **Symfony 7**, offering a robust **admin dashboard** and a seamless **customer interface**. The application is designed with scalability, security, and modularity in mind to ensure maintainability and high performance. SQL is used as primary databse , and DynamoDB is used to store logs and for analytical purpose. Advanced technologies like **AWS API Gateway**, **SQS**, and **Lambda** are integrated to handle email registration and verification securely.

## Key Features

- **Responsive Frontend**: Built using **Twig templates** and **Bootstrap 5**, ensuring a mobile-optimized, user-friendly experience across devices. Uses **Chart.js** for responsive and dynamic charts and diagrams for analytics.
- **Backend with Symfony 7**: Powered by **Symfony 7**, utilizing **Doctrine ORM** for efficient database management and object-relational mapping.
- **Database**: Utilizes **MySQL**, with database migrations handled seamlessly through **Phinx** for version control and smooth updates. Utilizes **DynamoDb** for storing logs and analytical purposes.
- **Advanced Security**: Implements **AWS API Gateway**, **SQS**, and **Lambda** for secure email registration and verification, integrated with **Mailtrap** for email handling.
- **Scalability**: **Terraform-ready** infrastructure, allowing for easy scaling and cloud deployment.
- **Modular Architecture**: Follows best practices in modular design and clean code, ensuring maintainability and flexibility for future enhancements.

## Prerequisites

Ensure the following dependencies are installed on your local machine before starting the setup:

- **PHP 8.1 or higher**
- **Composer**
- **MySQL** (for local database setup)

## Installation

Follow the steps below to set up the project locally:

### 1. Install Composer Dependencies

Run the following command to install the required PHP dependencies:

```bash
composer install
```

This command will install all dependencies and create the vendor folder automatically.

### 2. Install Phing
Phing is required for managing the build process. Install it by running:

```bash
Copy code
composer require --dev phing/phing
```

### 3. Generate the .env file and Setup Project
Once Phing is installed, execute the following command to build the project and generate the .env file:

```bash
Copy code
php ./vendor/bin/phing -D --env=dev build
```
This will:

* Set up necessary project dependencies.
* Generate the .env file containing required environment variables.
* Run migrations.
* Create demo data in your database , utilising doctrine fixtures and foundry factory. 
* Check and fix any structural errors in the project code, preparing it for local execution via Symfony's built-in server.

### 4. Start Symfony Server
To start the Symfony development server, run:

```bash
Copy code
symfony serve
```
Once the server is running, navigate to http://localhost:8000 in your browser to view the application in action.

## Demo
A live demonstration of the application hosted in the AWS Cloud is available upon request. Please reach out if you'd like to see the app in action.

## Codebase
The full codebase for this Ecommerce application is available on GitHub. You can explore the following repositories:

### 1.Main Project Code:
***GitHub Repository Link***

### 2.Terraform Configuration for Infrastructure:
***Terraform Repository Link***

The Terraform code includes:

* Load Balanced and Auto-Scaled Server Configuration
* RDS Database Setup for MySQL
* Mailer Functionality: Integrated with AWS API Gateway, SQS, and Lambda

## License
This project is licensed under the MIT License. See the LICENSE file for more details.