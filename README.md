# PHP Inventory Tracking System
> A lightweight Inventory Management System built with PHP and SQL Server, created as a focused project to demonstrate backend development using PDO/ODBC, Transact-SQL, and stored procedures. Built for portfolio purposes to showcase proficiency with SQL Server integration, secure PHP backend structure, and real-world database design

## Table of Contents
- [How to install](#how-to-install)
- [Requirements](#requirements)
- [How to run](#how-to-run)
- [Features](#features)
- [Project Structure](#project-structure)
- [Adding New Features](#adding-new-features)

## How to install

1. Releases are kept [here](https://github.com/Kazmania21/php-inventory-system/tags).

2. After installing a release, run composer install to download the dependencies.

3. Add the following environment variables to a new .env in the root directory (replace what is in the {}):

```
DB_SERVER={IpOrDomainHere}
DB_NAME={NameOfDatabase}
DB_USER={YourSqlServerUsername}
DB_PASS={YourSqlServerPassword}
```

5. Restore from the database backup within the database/backups folder. Below is the instuctions for Windows (I have only done this for Windows so far)
    - Move the .bak file to the C:\ folder to avoid issues with permissions (do not have it in your documents folder)
    - Create C:\SQLData\ if it does not exist already
    - Create C:\SQLLog\ if it does not exist already
    - Run the script below using sqlcmd (replace what is in the {}):

```
RESTORE DATABASE {NameOfDatabase}
FROM DISK = '{LocationOfBackupFile}'
WITH
    MOVE 'InventoryDB_Data' TO 'C:\SQLData\InventoryDB.mdf',
    MOVE 'InventoryDB_Log'  TO 'C:\SQLLog\InventoryDB_log.ldf',
    REPLACE,
    STATS = 10;
GO
```

## Requirements

php v8.3 or higher

## How to run

```bash
php -S localhost:8000 router.php
```

## Features

1. CRUD operations to manage inventory products.
2. Future login feature just to have the PHP code for it.

## Project Structure

This project follows a modular and scalable architecture with clear separation of concerns:

```text
├── assets/ # Stores assets like JS
├── assets/js # Stores JS assets
├── database/ # Stored procedures, migrations, and backups
├── database/backups # Database backups
├── database/migrations # Database migrations for Flyway to keep track of changes
├── database/stored_procedures # Scripts to create or alter stored procedures
├── forms/ # Input validation forms for different features
├── pages/ # PHP files for constructing html pages
├── composer.json # Project dependencies and scripts
├── db.php # Class that connects to SQL Server and executes queries
├── index.php # Contains routes connecting the view and model
├── router.php # Routes requests to either static files or routes defined in index.php
```

### Adding New Features

1. Create or update the database tables and stored procedures in `database/migrations`.
2. Write validation logic in a new or existing file in `forms/`.
3. Add new business/data logic to that same file in `forms/` (will make services folder for this instead).
4. Define new routes or extend existing ones in `index.php`.
5. Add new pages or update existing ones in `pages/`.
5. Add new frontend scripts or styling in `assets/`.
