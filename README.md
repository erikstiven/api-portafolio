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
  <a href="https://github.com/erikstiven/api-portafolio/stargazers">
    <img src="https://img.shields.io/github/stars/erikstiven/api-portafolio?style=for-the-badge" alt="Stars">
  </a>
  <a href="https://github.com/erikstiven/api-portafolio/network/members">
    <img src="https://img.shields.io/github/forks/erikstiven/api-portafolio?style=for-the-badge" alt="Forks">
  </a>
  <a href="https://github.com/erikstiven/api-portafolio/blob/main/LICENSE">
    <img src="https://img.shields.io/github/license/erikstiven/api-portafolio?style=for-the-badge" alt="License">
  </a>
</p>

---

## 🌍 API en Producción
🔗 **Base URL**: https://portafolio.codecima.com/api/v1/proyectos

Ejemplo de endpoint:
```bash
GET [https://api.tudominio.com/v1/proyectos](https://portafolio.codecima.com/api/v1/proyectos)


⚙️ Instalación Local

Clonar el repositorio:

git clone https://github.com/erikstiven/api-portafolio.git
cd api-portafolio


Instalar dependencias:

composer install
npm install


Configurar variables de entorno en .env:

APP_NAME=PortafolioAPI
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portafolio
DB_USERNAME=root
DB_PASSWORD=

CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME


Ejecutar migraciones y seeders:

php artisan migrate --seed


Levantar servidor:

php artisan serve

📌 Endpoints Principales
🔑 Autenticación
POST /auth/login

📂 Categorías
GET /categorias
POST /categorias

💼 Proyectos
GET /proyectos
POST /proyectos

