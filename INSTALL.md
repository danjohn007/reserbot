# Guía de Instalación Rápida - ReserBot

Esta guía te ayudará a instalar ReserBot en tu servidor en pocos minutos.

## Requisitos Previos

Antes de comenzar, asegúrate de tener:
- ✅ PHP 7.4 o superior
- ✅ MySQL 5.7 o superior  
- ✅ Servidor Apache con mod_rewrite habilitado
- ✅ Acceso a phpMyAdmin o línea de comandos MySQL

## Instalación Paso a Paso

### Paso 1: Obtener el Código

**Opción A: Clonar desde Git**
```bash
git clone https://github.com/danjohn007/reserbot.git
cd reserbot
```

**Opción B: Descargar ZIP**
1. Descarga el ZIP desde GitHub
2. Extrae los archivos en tu servidor web
3. Coloca en `htdocs/reserbot` o `www/reserbot`

### Paso 2: Crear la Base de Datos

**Usando phpMyAdmin:**
1. Abre phpMyAdmin en tu navegador
2. Crea una nueva base de datos llamada `reserbot`
3. Selecciona la base de datos
4. Ve a la pestaña "Importar"
5. Selecciona el archivo `database.sql` del proyecto
6. Haz clic en "Continuar"

**Usando línea de comandos:**
```bash
mysql -u root -p
CREATE DATABASE reserbot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reserbot;
SOURCE database.sql;
EXIT;
```

### Paso 3: Configurar la Conexión

Edita el archivo `config/config.php` y ajusta estas líneas:

```php
// Database Configuration
define('DB_HOST', 'localhost');      // Tu servidor MySQL
define('DB_NAME', 'reserbot');       // Nombre de tu base de datos
define('DB_USER', 'root');           // Tu usuario MySQL
define('DB_PASS', '');               // Tu contraseña MySQL
```

### Paso 4: Configurar Permisos

```bash
chmod 755 logs/
```

### Paso 5: Verificar Instalación

Abre tu navegador y navega a:
```
http://localhost/reserbot/test_connection.php
```

Si todos los tests pasan (✅), ¡tu instalación está completa!

### Paso 6: Probar el Módulo de Configuraciones (Opcional)

```
http://localhost/reserbot/test_configuraciones.php
```

### Paso 7: Acceder al Sistema

```
http://localhost/reserbot/
```

## Credenciales por Defecto

### Superadministrador
```
Email: admin@reserbot.com
Contraseña: ReserBot2024
```

### Cliente de Prueba
```
Email: juan.perez@email.com
Contraseña: ReserBot2024
```

Para más credenciales, consulta el README.md

## Solución de Problemas Comunes

### Error: "Página no encontrada"
**Solución:** Verifica que mod_rewrite esté habilitado en Apache
```bash
# En Ubuntu/Debian
sudo a2enmod rewrite
sudo service apache2 restart
```

### Error: "No se puede conectar a la base de datos"
**Solución:**
1. Verifica que MySQL esté corriendo
2. Verifica las credenciales en `config/config.php`
3. Asegúrate de que la base de datos `reserbot` exista

### Error: "Directorio logs no escribible"
**Solución:**
```bash
chmod 755 logs/
```

### Las URLs no funcionan correctamente
**Solución:** El sistema detecta automáticamente la URL base. Si tienes problemas:
1. Verifica que el archivo `.htaccess` exista
2. Verifica que mod_rewrite esté habilitado
3. Revisa el test de conexión para ver la URL detectada

## Configuración Adicional

### Cambiar a Modo Producción

En `config/config.php`:
```php
define('APP_ENV', 'production');
```

Y en `.htaccess`:
```apache
php_flag display_errors Off
```

### Configurar Email (Opcional)

Inicia sesión como Superadmin y ve a:
```
Dashboard → Configuraciones → Email / SMTP
```

## Siguientes Pasos

1. **Cambiar contraseñas por defecto**
   - Inicia sesión con cada usuario
   - Ve a "Mi Perfil"
   - Cambia la contraseña

2. **Configurar el sistema**
   - Como Superadmin, ve a Configuraciones
   - Personaliza nombre del sitio, colores, etc.

3. **Agregar tus datos**
   - Actualiza sucursales con tus ubicaciones
   - Modifica servicios según tu negocio
   - Configura especialistas

4. **Probar el sistema**
   - Crea una reservación de prueba
   - Verifica el flujo completo
   - Revisa los dashboards de cada rol

## Soporte

Si encuentras algún problema:
1. Revisa el README.md completo
2. Ejecuta los scripts de test
3. Revisa los logs en el directorio `logs/`
4. Abre un issue en GitHub

## Actualización

Para actualizar a una nueva versión:
```bash
git pull origin main
```

Luego ejecuta cualquier script de migración de base de datos si es necesario.

---

¡Listo! Tu sistema ReserBot está instalado y listo para usar. 🚀
