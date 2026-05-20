# RentSmart

RentSmart is a PHP and MySQL property marketplace for browsing rentals, saving favorite homes, publishing landlord listings, and managing inquiries through a clean server-rendered interface.

## Overview

The project was prepared as a realistic rental-platform demo with polished listing data, property galleries, renter favorites, landlord listing management, and direct inquiry flows.

## Features

- Featured property discovery on the homepage
- Search and filter listings by city, type, price, and bedrooms
- Property detail pages with galleries, amenities, and landlord contact options
- Favorites for renter accounts
- Landlord dashboard for posting, editing, and managing listings
- Amenities and features selection with database persistence
- Property image uploads and gallery support
- Contact and messaging flows for inquiries

## Technology Stack

- PHP 8
- MySQL / MariaDB
- HTML5
- Tailwind CSS
- Custom CSS
- JavaScript
- Apache / XAMPP

## Project Structure

- `public/` application entry point and public assets
- `src/Controllers/` request handling logic
- `src/Models/` database access and business rules
- `src/Services/` uploads and supporting services
- `resources/views/` server-rendered PHP templates
- `database/` schema and demo setup scripts

## Setup

1. Place the project inside your XAMPP `htdocs` directory.
2. Start Apache and MySQL from XAMPP.
3. Update database credentials in `config/config.php` if needed.
4. Run the setup script:

```bash
php database/setup.php
```

To rebuild the full presentation dataset, run:

```bash
php database/setup.php --fresh-demo
```

5. Open the app in the browser using your local XAMPP URL.

## Demo Notes

The presentation dataset includes renter, landlord, and admin accounts so the main user flows can be demonstrated quickly during evaluation.

## Screenshots

Add final screenshots here before submission:

- Homepage
- Search results
- Property detail page
- Renter dashboard
- Landlord dashboard
- Listing form

## Group Information

GROUP 7

IBSA ABERA — ETS0734/16
JIBRIL MAYGAG — ETS0755/16
HIRUY HABTAMU — ETS0717/16
KALEAB SOLOMON — ETS0758/16
KENEAN AYALEW — ETS0796/16
KIDUS MULUGETA — ETS0828/16
