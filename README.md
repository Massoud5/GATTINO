# GATTINO Grocery

## Context:

Creating a Website that allow the admin(the grocery owner) to publish her grocery services and to sale her products online without delivery.
The user or customer won't have an account but he/she can get informed about the store, proposed services and different products.
In the Website, the user can also order products, gift packages and plates and pay it online with a visa card or mastercard. A bill of order will be sent to the user and admin. Then the command can be retrieved in the store. 
The customer can be a company and have a big command so orders should be sent at least 24 hours before retrieval time.
The Website should be fonctional with a standard UX conception.

## Overview

GATIINO Grocery is an intuitive click-and-collect web application developed with the Symfony framework, designed to empower grocery store owners to seamlessly manage their services and sell products online. This user-friendly platform provides customers with the convenience of exploring a diverse array of products, placing orders, and processing payments securely—all without the necessity of creating an account.

## Fonctionnality Features

- **User-friendly design**: An original design made with css native and Bootstrap.
- **Product Search Bar**: An efficient search functionality that allows users to quickly find products within the extensive catalog by entering keywords or phrases.
- **Product Catalog**: View a list of available grocery items, services, and specials.
- **Order Placement**: Users can order products, and platters online.
- **Secure Payment Integration**: The application incorporates Stripe for payment processing, offering robust security and support for transactions made with Visa and Mastercard.
- **Order Confirmation**: Automatic generation of a bill of order sent to both user and admin.
- **Order Retrieval**: Functionality for order pick-up scheduling, with a 24-hour advance notice requirement.
- **Administrative Dashboard**: Equipped with a comprehensive control panel allowing for the creation, updating, and removal of products, orders, services, and catalog entries with ease and efficiency.
- **Dynamic Content Updating**: Utilizes Ajax to refresh and update content dynamically without the need for full page reloads, ensuring a smooth and uninterrupted user experience.

## Security Features
- **Spam Protection**: Integration of Google reCAPTCHA to safeguard forms against automated abuse and ensure submissions are genuine.
- **Authentification System**: A secure login mechanism exclusively for the administrative user to ensure protected access to the management features of the platform.
- **Two-Factor Authentication**: Enhanced security through a two-step verification process, requiring both a password and a verification code sent via email to access administrative accounts.

## Getting Started

### Prerequisites

- PHP version 8.1
- Composer
- Symfony 6.3

### Installation

1. Clone the repository: `git clone git@github.com:Massoud5/GATTINO.git`
2. Navigate to the project directory: `cd GATTINO`
3. Install dependencies: `composer install`
4. Configure your environment variables in `.env` file.

### Running the Application

1. install [symfony-CLI](https://symfony.com/download) in your system
2. Start the server: `symfony server:start`
3. Access the application at `https://localhost:8000`


## Contributing

- Fork the repository
- Create a feature branch: `git checkout -b new-feature`
- Commit changes: `git commit -am 'Add some feature'`
- Push to the branch: `git push origin new-feature`
- Submit a pull request

## License

MIT License

Copyright (c) [2023] [Massoud]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following condition:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Application.