# Quizić - Online quiz application
Quizić is an online quiz application built with Laravel and Vue.js. Project includes registration/login functionality, CRUD for questions that are stored across 3 levels of difficulty, hints system, highscore leaderboard, etc.
## How to use
### Setup
1. Clone the repository with **git clone**.
2. Copy **.env.example** file to **.env** and edit database credentials there.
3. Run the following commands:
```
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan serve
```
4. Load the homepage through the following link: [localhost:8000](localhost:8000).
### Usage
* To log in as administrator: **Administrator** / **Quizic123**.
* To log in as guest: **JohnDoe** / **Quizic123**.
* Or try Quizić without logging in by clicking on the designated button.
## Built with
- **[Laravel](https://laravel.com/)** - Back-End framework.
- **[Vue.js](https://vuejs.org/)** - Front-End framework.
* **[jwt-auth](https://github.com/tymondesigns/jwt-auth)** - JSON Web Token Authentication for Laravel & Lumen.
* **[SweetAlert2](https://sweetalert2.github.io)** - A beautiful, responsive, highly customizable and accessible (WAI-ARIA) replacement for JavaScript's popup boxes. Zero dependencies.
* **[VeeValidate](https://github.com/baianat/vee-validate)** - Template Based Validation Framework for Vue.js.
* **[vue-auth](https://github.com/websanova/vue-auth)** - Jwt Auth library for Vue.js.
* **[vue-resource](https://github.com/pagekit/vue-resource)** - The HTTP client for Vue.js.
* **[vue-router](https://github.com/vuejs/vue-router)** - The official router for Vue.js.
* **[v-tooltip](https://github.com/Akryum/v-tooltip)** - Easy tooltips, popovers, dropdown for Vue 2.x.
### Authors
* **Filip Jakovljević** - Back-End and Vue.js.
* **Nemanja Stojković** - Design and Front-End.
* **Lazar Stanojević** - Desing and Front-End.
