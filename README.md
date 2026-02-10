<p align="center">
  <img src="https://laravel.com/img/logotype.min.svg" alt="Laravel Logo" width="200">
  <img src="https://vuejs.org/images/logo.png" alt="Vue.js Logo" width="100">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Logo" width="100">
  <img src="https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D" alt="Vue.js Logo" width="100">
</p>

# 🧩 Quizić - Online Quiz Application

Quizić is an online quiz application built with **Laravel** 🐘 and **Vue.js** 🖖. The project includes registration and login functionalities, CRUD operations for quiz questions stored across three difficulty levels, a hint system, a high-score leaderboard, and much more!

---

## 🚀 Getting Started

### 🐳 Docker Setup (Recommended)

1. Clone the repository:
   ```bash
   git clone https://github.com/JakovljevicFilip/quizic
   cd quizic
   ```

2. Make Docker scripts executable:
   ```bash
   sudo chmod +x docker/scripts/start-local.sh
   sudo chmod +x docker/scripts/fix-permissions.sh
   sudo chmod +x docker/php/entrypoint.sh
   ```

3. Start Docker containers:
   ```bash
   docker/scripts/start-local.sh
   ```

4. Access the homepage:
   ```
   http://localhost:8000
   ```

* For production deployments (prod-only setup):
    ```bash
    sudo chmod +x docker/scripts/start-prod.sh
    sudo chmod +x docker/scripts/fix-permissions.sh
    sudo chmod +x docker/php/entrypoint.sh
    docker/scripts/start-prod.sh

    # Optional: start the demo reseed job (runs daily)
    docker compose -f docker-compose.prod.yml --profile cron up -d cron
    ```

---

### 🛠️ Regular Setup

#### Requirements:

![PHP](https://img.shields.io/badge/PHP-%3E=7.4-blue?logo=php&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-%3E=2.x-blue?logo=composer&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-%3E=5.7-blue?logo=mysql&logoColor=white)
![Node.js](https://img.shields.io/badge/Node.js-12.x%20to%2014.x-brightgreen?logo=node.js&logoColor=white)
![npm](https://img.shields.io/badge/npm-%E2%9C%94-red?logo=npm&logoColor=white)
![phpMyAdmin](https://img.shields.io/badge/phpMyAdmin-Optional-orange)

1. Clone the repository:
   ```bash
   git clone https://github.com/JakovljevicFilip/quizic
   ```

2. Copy `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```

3. Edit database credentials in `.env`.

4. Create a database using phpMyAdmin or a similar database management tool.

5. Run the following commands:
   ```php
   composer install
   php artisan key:generate
   php artisan jwt:secret
   php artisan config:cache
   php artisan migrate --seed
   php artisan serve
   ```

6. Access the homepage:
   ```
   http://localhost:8000
   ```

---

## 🎮 Usage

- 🔐 To log in as administrator: **Administrator** / **Quizic123**.
- 👤 To log in as guest: **JohnDoe123** / **Quizic123**.
- 🚪 Or try Quizić without logging in by clicking the designated button.

---

## 🧪 Seeding with Fake Questions

> [!CAUTION]
> This seeder replaces the existing questions and answers table data. Intended for local development or testing environments only.


To seed the database with fake questions and answers (10 easy, 20 moderate, 40 hard), run the following command:
   ```bash
  php artisan db:seed --class=FakeQuestionsAndAnswersSeeder
   ```
Or if you're using Docker:
   ```bash
   docker compose exec app php artisan db:seed --class=FakeQuestionsAndAnswersSeeder
   ```

### 🔁 Restoring Original Questions

> [!NOTE]
> This will repopulate the database with the original curated trivia questions, grouped by difficulty.
