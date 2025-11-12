# ReserBot - Sistema de Reservaciones y Citas Profesionales

Sistema completo de gestión de reservaciones y citas con múltiples niveles de acceso, desarrollado en PHP puro con arquitectura MVC.

## 🚀 Características Principales

- **Autenticación y Registro**: Sistema completo de login con roles diferenciados
- **5 Niveles de Acceso**: Superadmin, Admin de Sucursal, Especialista, Cliente y Recepcionista
- **Gestión de Reservaciones**: Agenda citas con verificación de disponibilidad en tiempo real
- **Múltiples Sucursales**: Gestión de diferentes ubicaciones
- **Especialistas y Servicios**: Catálogo completo de profesionales y servicios
- **Horarios Dinámicos**: Los especialistas definen sus horarios, vacaciones y bloqueos
- **Sistema de Calificaciones**: Evaluación de servicios y especialistas
- **Logs de Seguridad**: Auditoría completa de acciones en el sistema
- **Diseño Responsivo**: Interface moderna con Tailwind CSS

## 📋 Requisitos del Sistema

- **PHP**: 7.4 o superior
- **MySQL**: 5.7 o superior
- **Apache**: 2.4+ con mod_rewrite habilitado
- **Extensiones PHP**: PDO, PDO_MySQL

## 🛠️ Instalación

### 1. Clonar o Descargar el Repositorio

```bash
git clone https://github.com/danjohn007/reserbot.git
cd reserbot
```

O descarga el archivo ZIP y extráelo en tu servidor web.

### 2. Configurar el Servidor Web

#### Para Apache (XAMPP, WAMP, LAMP)

1. Coloca el proyecto en tu directorio web (ej: `htdocs/reserbot` o `www/reserbot`)
2. El proyecto incluye un archivo `.htaccess` que maneja el URL rewriting
3. Asegúrate de que `mod_rewrite` esté habilitado en Apache

#### Para otros servidores

El sistema detecta automáticamente la URL base, pero puedes instalarlo en cualquier subdirectorio.

### 3. Crear la Base de Datos

1. Abre phpMyAdmin o tu gestor de MySQL preferido
2. Crea una nueva base de datos llamada `reserbot`
3. Importa el archivo `database.sql` incluido en el proyecto
4. Este archivo creará todas las tablas necesarias y poblará la base de datos con datos de ejemplo

```sql
mysql -u root -p < database.sql
```

O usando phpMyAdmin:
- Selecciona la base de datos `reserbot`
- Ve a "Importar"
- Selecciona el archivo `database.sql`
- Haz clic en "Continuar"

### 4. Configurar la Conexión a la Base de Datos

Edita el archivo `config/config.php` y ajusta las credenciales de tu base de datos:

```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'reserbot');
define('DB_USER', 'root');        // Tu usuario de MySQL
define('DB_PASS', '');            // Tu contraseña de MySQL
define('DB_CHARSET', 'utf8mb4');
```

### 5. Configurar Permisos

Asegúrate de que el directorio `logs/` tenga permisos de escritura:

```bash
chmod 755 logs/
```

### 6. Probar la Instalación

Abre tu navegador y navega a:

```
http://localhost/reserbot/test_connection.php
```

Este script verificará:
- ✅ Versión de PHP
- ✅ Detección de URL base
- ✅ Estructura de directorios
- ✅ Permisos de escritura
- ✅ Extensiones PHP requeridas
- ✅ Conexión a la base de datos
- ✅ Existencia de tablas

Si todos los tests pasan, ¡tu instalación está completa!

### 7. Acceder al Sistema

Navega a la URL de tu instalación:

```
http://localhost/reserbot/
```

## 🔐 Credenciales de Acceso

El sistema incluye usuarios de prueba con diferentes roles:

### Superadministrador
- **Email**: admin@reserbot.com
- **Contraseña**: ReserBot2024

### Administrador de Sucursal (Centro Histórico)
- **Email**: admin.centro@reserbot.com
- **Contraseña**: ReserBot2024

### Administrador de Sucursal (Juriquilla)
- **Email**: admin.juriquilla@reserbot.com
- **Contraseña**: ReserBot2024

### Especialistas
- **Dra. Ana López (Médico)**: ana.lopez@reserbot.com / ReserBot2024
- **Dr. Roberto Hernández (Odontólogo)**: roberto.hernandez@reserbot.com / ReserBot2024
- **Lic. Patricia Ramírez (Abogada)**: patricia.ramirez@reserbot.com / ReserBot2024
- **Mtro. Fernando Silva (Contador)**: fernando.silva@reserbot.com / ReserBot2024

### Cliente
- **Juan Pérez**: juan.perez@email.com / ReserBot2024
- **Laura Sánchez**: laura.sanchez@email.com / ReserBot2024

### Recepcionista
- **Sofía Torres**: sofia.torres@reserbot.com / ReserBot2024

## 📱 Uso del Sistema

### Para Clientes

1. **Registrarse**: Crear una cuenta desde la página principal
2. **Iniciar Sesión**: Acceder con email y contraseña
3. **Nueva Reservación**:
   - Seleccionar sucursal
   - Elegir servicio
   - Seleccionar especialista
   - Elegir fecha y hora disponible
   - Confirmar reservación
4. **Gestionar Citas**: Ver, modificar o cancelar reservaciones desde el dashboard

### Para Especialistas

1. **Iniciar Sesión**: Acceder con credenciales de especialista
2. **Ver Citas**: Dashboard muestra citas del día y próximas
3. **Gestionar Citas**: Confirmar, completar o gestionar reservaciones
4. **Calificaciones**: Ver feedback de clientes

### Para Administradores

1. **Panel de Control**: Vista general del sistema
2. **Gestionar Sucursales**: Crear y administrar ubicaciones
3. **Especialistas**: Dar de alta y asignar servicios
4. **Reportes**: Ver estadísticas y métricas del sistema
5. **Usuarios**: Administrar cuentas y roles

### Para Recepcionistas

1. **Ver Citas del Día**: Lista de todas las citas programadas
2. **Confirmar Reservaciones**: Confirmar citas pendientes
3. **Gestión Rápida**: Acceso rápido a información de clientes

## 🏗️ Estructura del Proyecto

```
reserbot/
├── app/
│   ├── controllers/         # Controladores MVC
│   │   ├── BaseController.php
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   └── ReservacionController.php
│   ├── models/             # Modelos de datos
│   │   ├── Usuario.php
│   │   ├── Sucursal.php
│   │   ├── Servicio.php
│   │   ├── Especialista.php
│   │   └── Reservacion.php
│   └── views/              # Vistas
│       ├── layouts/        # Plantillas reutilizables
│       ├── home/           # Página principal
│       ├── auth/           # Login y registro
│       ├── dashboard/      # Dashboards por rol
│       └── reservations/   # Gestión de reservaciones
├── config/
│   ├── config.php          # Configuración principal
│   └── database.php        # Conexión a BD
├── public/
│   ├── css/               # Estilos personalizados
│   ├── js/                # JavaScript
│   └── images/            # Imágenes
├── logs/                  # Archivos de log
├── .htaccess             # Configuración Apache
├── .gitignore            # Archivos ignorados por Git
├── index.php             # Front controller
├── test_connection.php   # Test de instalación
├── database.sql          # Schema y datos de ejemplo
└── README.md            # Este archivo
```

## 📊 Datos de Ejemplo

El sistema incluye:
- 3 Sucursales en Querétaro (Centro Histórico, Juriquilla, Corregidora)
- 6 Categorías de servicios
- 10 Servicios diferentes
- 4 Especialistas con horarios configurados
- Reservaciones de ejemplo
- Días festivos de México

## 🔒 Seguridad

- Contraseñas hasheadas con `password_hash()`
- Protección CSRF en formularios
- Validación y sanitización de inputs
- Control de intentos de login fallidos
- Bloqueo temporal de cuentas
- Logs de auditoría completos
- Protección contra SQL Injection con PDO preparado

## 📝 Tecnologías Utilizadas

- **Backend**: PHP 7.4+ (puro, sin frameworks)
- **Base de Datos**: MySQL 5.7
- **Frontend**: 
  - HTML5
  - Tailwind CSS 3.x
  - JavaScript (Vanilla)
  - Font Awesome 6.x
- **Arquitectura**: MVC
- **Servidor**: Apache 2.4+

## 🔧 Configuración Avanzada

### URL Base Automática

El sistema detecta automáticamente la URL base, lo que permite instalarlo en cualquier directorio sin configuración adicional.

### Modo de Desarrollo vs Producción

En `config/config.php`, ajusta:

```php
define('APP_ENV', 'production'); // Cambiar a 'production' para ocultar errores
```

### Configuración de Sesiones

Ajusta el tiempo de vida de las sesiones en `config/config.php`:

```php
define('SESSION_LIFETIME', 7200); // 2 horas en segundos
```

## 🐛 Solución de Problemas

### Error: "Página no encontrada"
- Verifica que `mod_rewrite` esté habilitado en Apache
- Verifica que el archivo `.htaccess` exista y tenga el contenido correcto

### Error de conexión a la base de datos
- Verifica las credenciales en `config/config.php`
- Asegúrate de que MySQL esté corriendo
- Verifica que la base de datos `reserbot` exista

### Directorio logs no escribible
```bash
chmod 755 logs/
```

### URLs no funcionan correctamente
- El sistema detecta automáticamente la URL base
- Si tienes problemas, verifica `test_connection.php`

## 📞 Soporte

Para reportar problemas o sugerencias:
- Abre un issue en GitHub
- Email: info@reserbot.com

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 👥 Contribuir

Las contribuciones son bienvenidas. Por favor:
1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 🎯 Roadmap

Próximas características planeadas:
- [ ] Módulo de configuraciones del sistema
- [ ] Integración con WhatsApp
- [ ] Sistema de notificaciones por email
- [ ] Integración con PayPal para pagos
- [ ] Calendario visual interactivo
- [ ] Reportes y estadísticas avanzadas
- [ ] API REST para integraciones
- [ ] Aplicación móvil

---

Desarrollado con ❤️ para la comunidad de código abierto
