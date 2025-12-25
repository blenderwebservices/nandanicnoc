
# Nanda Search Bar ⚕️

**Nanda Search Bar** es una herramienta digital diseñada para optimizar el flujo de trabajo de los profesionales de enfermería y estudiantes del área de la salud. Esta aplicación facilita la búsqueda y consulta de diagnósticos **NANDA**, intervenciones **NIC** y objetivos **NOC**, relacionándolos de manera inteligente, rápida y eficaz.

El objetivo principal es reducir el tiempo dedicado a la planificación de cuidados, ofreciendo sugerencias precisas y basadas en evidencia al instante.

## 🚀 Demo en vivo

Puedes ver un despliegue preliminar del proyecto funcionando aquí:
👉 **[https://nanda.axiacorehub.com](https://nanda.axiacorehub.com)**

## ✨ Características Principales

* **Buscador Inteligente:** Localiza diagnósticos por código, palabras clave o categorías.
* **Relación NANDA-NIC-NOC:** Algoritmo que sugiere intervenciones (NIC) y resultados (NOC) coherentes basados en el diagnóstico seleccionado.
* **Interfaz Intuitiva:** Diseño limpio y fácil de usar, potenciado por FilamentPHP.
* **Multidioma:** Soporte preliminar para consulta en español e inglés.
* **Acceso Rápido:** Información detallada sobre definiciones, factores de riesgo y características definitorias.

## 🛠️ Tecnologías Utilizadas

Este proyecto está construido sobre un stack robusto y moderno:

* **Framework:** [Laravel](https://laravel.com/) (PHP)
* **Panel de Administración & UI:** [FilamentPHP](https://filamentphp.com/) - Para una interfaz administrativa y de usuario ágil y reactiva.
* **Base de Datos:**
    * **Actual:** **SQLite** (para desarrollo ágil y despliegue inicial).
    * **Próximamente:** Migración a **MySQL**.
    * **Objetivo Final:** Implementación en **PostgreSQL** para entornos de producción de alto rendimiento.

## 📦 Instalación y Despliegue Local

Sigue estos pasos para configurar el proyecto en tu entorno local:

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/blenderwebservices/nandanicnoc.git](https://github.com/blenderwebservices/nandanicnoc.git)
    cd nandanicnoc
    ```

2.  **Instalar dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Configurar variables de entorno:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Por defecto, el proyecto está configurado para usar SQLite, así que no necesitas configurar credenciales de base de datos adicionales por ahora.*

4.  **Crear la base de datos y correr migraciones:**
    ```bash
    touch database/database.sqlite
    php artisan migrate
    ```

5.  **Crear un usuario administrador (para Filament):**
    ```bash
    php artisan make:filament-user
    ```

6.  **Ejecutar el servidor de desarrollo:**
    ```bash
    php artisan serve
    ```

7.  Abre tu navegador en `http://localhost:8000`.

## 🤝 Contribución

¡Las contribuciones son bienvenidas! Si tienes ideas para mejorar la relación de los diagnósticos o nuevas funcionalidades:

1.  Haz un Fork del proyecto.
2.  Crea una rama para tu funcionalidad (`git checkout -b feature/NuevaFuncionalidad`).
3.  Haz Commit de tus cambios (`git commit -m 'Agrega nueva funcionalidad'`).
4.  Haz Push a la rama (`git push origin feature/NuevaFuncionalidad`).
5.  Abre un Pull Request.

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo `LICENSE` para más detalles.

---
Desarrollado con ❤️ por [blenderwebservices](https://github.com/blenderwebservices) para la comunidad de enfermería.

