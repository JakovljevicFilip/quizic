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
   ```

2. Make Docker scripts executable:
   ```bash
   sudo chmod +x fix-permissions.sh
   sudo chmod +x docker/php/entrypoint.sh
   ```

3. Start Docker containers:
   ```bash
   docker compose up --build
   ```

4. Access the homepage:
   ```
   http://localhost
   ```

---

### 🛠️ Regular Setup

#### Requirements:
- PHP >= 7.4
- Composer
- MySQL Database
- phpMyAdmin (or similar)
- npm (Node.js)

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
   ```bash
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

## 🛠 Built With

- **[Laravel](https://laravel.com/)** - Back-End framework.
- **[Vue.js](https://vuejs.org/)** - Front-End framework.
- **[jwt-auth](https://github.com/tymondesigns/jwt-auth)** - JSON Web Token Authentication for Laravel.
- **[SweetAlert2](https://sweetalert2.github.io)** - Beautiful and responsive popup boxes.
- **[VeeValidate](https://github.com/baianat/vee-validate)** - Template-based validation for Vue.js.
- **[vue-auth](https://github.com/websanova/vue-auth)** - JWT authentication for Vue.js.
- **[vue-resource](https://github.com/pagekit/vue-resource)** - HTTP client for Vue.js.
- **[vue-router](https://github.com/vuejs/vue-router)** - Official router for Vue.js.
- **[v-tooltip](https://github.com/Akryum/v-tooltip)** - Tooltips, popovers, and dropdowns for Vue.

---

## 🧑‍💻 Authors

- **Filip Jakovljević** - Back-End and Vue.js.
- **Nemanja Stojković** - Design and Front-End.
- **Lazar Stanojević** - Design and Front-End.

