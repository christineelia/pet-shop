# PET SHOP

Welcome to my pet shop app!

## Features

- User authentication and authorization (admin and user)
- Create, read, update, and delete users and admins
- Get Users orders
- Get List of users for admin

## Getting Started

Instructions for installing the project:

1- Clone the repository
2- Install dependencies with composer install
3- Create a copy of .env.example and rename to .env
4- Generate a new application key with php artisan key:generate
5- Configure the database settings in .env
6- Run database migrations with php artisan migrate

## Configuration
Details about any configuration options:

JWT_PRIVATE_KEY: Private Key used for parsing the JWT token for authorisation
JWT_PUBLIC_KEY: Public Key used for parsing the JWT token for authorisation

## Usage
Instructions for using the application:

Visit the homepage at /api/documentation for Swagger documentation
Use the API endpoints at /api/v1/endpoint