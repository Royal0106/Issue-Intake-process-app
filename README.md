# Project Name

Issue Intake Project  

---

## Prerequisites

Before you begin, ensure you have the following installed:

- PHP >= 8.1
- Composer
- Laravel >= 10
- MySQL or other supported database
- Git

---

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo
```  

2. **Install dependencies**

```bash
composer install
npm install
```  


3. **Copy the environment file**

```bash
cp .env.example .env
``` 

4. **Generate the application key**

```bash
php artisan key:generate
``` 

5. **Configure your database**

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartapp
DB_USERNAME=root
DB_PASSWORD=
OPENAI_API_KEY=

``` 

## Database Setup

1. **Run migrations**

```bash
php artisan migrate
```  

2. **Seed the database**

```bash
php artisan db:seed
```  

## Running the Application

1. **Start the local development server**

```bash
php artisan serve
```  

2. **Open your browser and go to**

```bash
http://127.0.0.1:8000
```  



