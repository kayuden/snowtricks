# SnowTricks

Project 6: Develop the SnowTricks community site from A to Z - OpenClassrooms course: Application developer - PHP/Symfony

This project involves the development of a collaborative website in **Symfony**.  
The application allows users to share snowboard tricks with text, images and videos. Visitors can browse the tricks, and registered users can contribute content. The project also includes a secure authentication system and a user-friendly interface.

---

## Prerequisites

Before installing the project, ensure you have the following installed on your system:

- [**PHP >= 8.1**](https://www.php.net/downloads.php)  
- [**Composer**](https://getcomposer.org/download/)  
- [**Symfony CLI**](https://symfony.com/download) (optional but recommended)  
- [**PostgreSQL**](https://www.postgresql.org/download) or another database engine compatible with Doctrine ORM  
- [**Git**](https://git-scm.com/downloads) for cloning the repository  
- A modern web browser  

---

## Installation

Follow these steps to set up the project on your local machine:

### 1. Clone the Repository
```bash
git clone https://github.com/kayuden/snowtricks.git
cd snowtricks
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment Variables
Duplicate the `.env` file to create a `.env.local` file:
```bash
cp .env .env.local
```

Edit `.env.local` to configure your database connection (replace with your own credentials):
```
DATABASE_URL="postgresql://postgres:motdepasse@127.0.0.1:5432/snowtricks?serverVersion=17.2&charset=utf8"
```

### 4. Create the Database
```bash
php bin/console doctrine:database:create
```

### 5. Run Migrations
```bash
php bin/console doctrine:migrations:migrate
```

### 6. Load Fixtures (optional, to add demo data)
```bash
php bin/console doctrine:fixtures:load
```

### 7. Start the Local Server
Using Symfony CLI:
```bash
symfony serve
```

The application will be accessible at:  
👉 [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Contact

For questions or feedback, feel free to contact **kbartholomot@gmail.com**  
