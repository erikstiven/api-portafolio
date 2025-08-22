<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<h1 align="center">📂 API Portafolio</h1>

<p align="center">
API REST desarrollada en <b>Laravel</b> para gestionar un portafolio dinámico con autenticación JWT, categorías y proyectos con subida de imágenes en <b>Cloudinary</b>.
</p>

<p align="center">
  <a href="https://github.com/tu-usuario/api-portafolio/stargazers"><img src="https://img.shields.io/github/stars/tu-usuario/api-portafolio?style=for-the-badge" alt="Stars"></a>
  <a href="https://github.com/tu-usuario/api-portafolio/network/members"><img src="https://img.shields.io/github/forks/tu-usuario/api-portafolio?style=for-the-badge" alt="Forks"></a>
  <a href="https://github.com/tu-usuario/api-portafolio/blob/main/LICENSE"><img src="https://img.shields.io/github/license/tu-usuario/api-portafolio?style=for-the-badge" alt="License"></a>
</p>

---

## 🌍 API en Producción
🔗 **Base URL**: [https://api.tudominio.com/v1](https://api.tudominio.com/v1)

Ejemplo de endpoint:
```bash
GET https://api.tudominio.com/v1/proyectos



📑 Funcionalidades

🔐 Autenticación JWT (login de usuarios)

🗂 Gestión de Categorías (CRUD)

📸 Gestión de Proyectos con subida a Cloudinary

👤 Seeder inicial de admin

⚡ Arquitectura limpia con controladores y rutas versionadas

📦 Requisitos

PHP >= 8.1

Composer 2

MySQL/MariaDB

Extensiones PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON

⚙️ Instalación
git clone https://github.com/tu-usuario/api-portafolio.git
cd api-portafolio
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve

Variables .env principales
APP_NAME=Portafolio
DB_CONNECTION=mysql
DB_DATABASE=portafolio
DB_USERNAME=root
DB_PASSWORD=
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=

🚀 Endpoints
🔐 Autenticación

POST /auth/login → Login con token

🗂 Categorías

GET /categorias

POST /categorias

PUT /categorias/{id}

DELETE /categorias/{id}

📸 Proyectos

GET /proyectos

POST /proyectos

PUT /proyectos/{id}

DELETE /proyectos/{id}

Ejemplo POST /proyectos

Headers:

Authorization: Bearer {token}


Body (form-data):

titulo: "Mi Proyecto"
descripcion: "Descripción del proyecto"
imagen: archivo.jpg/png

🛠 Tecnologías utilizadas

Laravel 11

MySQL

Cloudinary

JWT Auth

🤝 Contribuciones

Haz un fork, crea tu rama y abre un PR.

📄 Licencia

MIT


---

👉 ¿Quieres que te lo deje **solo como comentarios dentro del código** (para que se vea al abrir cu
