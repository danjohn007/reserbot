# ReserBot - Lista Completa de Características

## 🎯 Visión General

ReserBot es un sistema completo de gestión de reservaciones y citas profesionales desarrollado en PHP puro con arquitectura MVC. Diseñado para ser fácil de instalar, seguro y altamente funcional.

## 📊 Estadísticas del Proyecto

- **Líneas de Código:** ~5,664
- **Archivos PHP:** 33
- **Modelos:** 6
- **Controladores:** 5
- **Vistas:** 20+
- **Tablas de Base de Datos:** 13
- **Usuarios de Prueba:** 10
- **Roles del Sistema:** 5

## 🔐 Sistema de Autenticación y Seguridad

### Autenticación
- ✅ Login con email y contraseña
- ✅ Registro de nuevos usuarios
- ✅ Cierre de sesión
- ✅ Protección CSRF en todos los formularios
- ✅ Hashing de contraseñas con bcrypt (password_hash)
- ✅ Validación de inputs
- ✅ Sanitización de datos

### Control de Acceso
- ✅ 5 niveles de acceso diferenciados
- ✅ Verificación de permisos en cada acción
- ✅ Redirección basada en rol
- ✅ Sesiones seguras con timeout configurable

### Seguridad Avanzada
- ✅ Tracking de intentos de login fallidos
- ✅ Bloqueo temporal de cuentas (configurable)
- ✅ Logs de seguridad completos
- ✅ Protección contra SQL Injection (PDO preparado)
- ✅ Protección contra XSS (htmlspecialchars)
- ✅ Tokens CSRF únicos por sesión

## 👥 Sistema de Roles

### 1. Superadministrador
**Acceso Completo al Sistema**
- Panel de estadísticas globales
- Gestión de todas las sucursales
- Gestión de todos los especialistas
- Gestión de todos los servicios
- Configuraciones del sistema
- Reportes completos
- Logs de seguridad
- Gestión de usuarios

### 2. Administrador de Sucursal
**Gestión de Sucursal Asignada**
- Panel de estadísticas de sucursal
- Gestión de reservaciones
- Ver especialistas de su sucursal
- Reportes de sucursal
- Confirmación de citas

### 3. Especialista
**Gestión de Agenda Personal**
- Dashboard con citas del día
- Vista de próximas citas
- Confirmación de reservaciones
- Completar citas
- Ver historial personal
- Ver calificaciones recibidas

### 4. Cliente
**Agendar y Gestionar Citas**
- Dashboard personal
- Nueva reservación (wizard completo)
- Ver citas próximas
- Ver historial de citas
- Cancelar reservaciones
- Ver detalles de citas

### 5. Recepcionista
**Gestión de Citas del Día**
- Ver todas las citas del día
- Confirmar reservaciones pendientes
- Ver detalles de clientes
- Acceso rápido a información

## 📅 Sistema de Reservaciones

### Proceso de Reservación
1. **Selección de Sucursal** - Todas las ubicaciones disponibles
2. **Categoría de Servicio** - Organización por categorías
3. **Servicio Específico** - Con precio y duración
4. **Especialista** - Filtrado por servicio y sucursal
5. **Fecha** - Calendario con validación
6. **Hora** - Horarios disponibles en tiempo real
7. **Notas** - Información adicional opcional

### Características de Reservación
- ✅ Verificación de disponibilidad en tiempo real
- ✅ Validación de horarios del especialista
- ✅ Respeto de días festivos
- ✅ Detección de conflictos de horario
- ✅ Consideración de duración del servicio
- ✅ Bloqueos de horario (vacaciones, personal)

### Estados de Reservación
- **Pendiente:** Recién creada, esperando confirmación
- **Confirmada:** Confirmada por recepcionista o especialista
- **Completada:** Servicio realizado
- **Cancelada:** Cancelada por cliente o administrador

### Gestión de Reservaciones
- ✅ Ver detalles completos
- ✅ Cancelar con motivo
- ✅ Actualizar estado
- ✅ Historial completo
- ✅ Filtrado por estado
- ✅ Filtrado por fecha

## 🏢 Gestión de Sucursales

### Información de Sucursales
- Nombre y descripción
- Dirección completa
- Ciudad y estado
- Código postal
- Teléfono y email
- Horario de apertura y cierre
- Coordenadas GPS (latitud/longitud)
- Estado activo/inactivo

### Funcionalidades
- ✅ Lista de todas las sucursales
- ✅ Vista detallada por sucursal
- ✅ Filtrado por ciudad/estado
- ✅ Asignación de administradores
- ✅ Asignación de especialistas

## 💼 Gestión de Servicios

### Categorías de Servicio
- Medicina General
- Odontología
- Servicios Legales
- Contabilidad
- Psicología
- Nutrición

### Información de Servicios
- Nombre y descripción
- Categoría
- Duración en minutos
- Precio
- Estado activo/inactivo
- Fecha de creación

### Catálogo de Servicios
- ✅ 10 servicios pre-configurados
- ✅ 6 categorías organizadas
- ✅ Iconos Font Awesome
- ✅ Precios configurables
- ✅ Duraciones flexibles

## 👨‍⚕️ Gestión de Especialistas

### Perfil de Especialista
- Información personal
- Sucursal asignada
- Especialidad
- Biografía
- Título profesional
- Cédula profesional
- Foto de perfil
- Calificación promedio
- Total de calificaciones

### Horarios del Especialista
- ✅ Horarios por día de semana
- ✅ Múltiples bloques por día
- ✅ Hora inicio y fin
- ✅ Activación/desactivación

### Bloqueos de Horario
- Vacaciones
- Motivos personales
- Enfermedad
- Otros

### Servicios Asignados
- ✅ Múltiples servicios por especialista
- ✅ Asignación flexible
- ✅ Filtrado por servicio

## 📊 Panel de Administración

### Gestión Completa
- ✅ **Sucursales:** CRUD completo
- ✅ **Servicios:** Catálogo completo
- ✅ **Especialistas:** Gestión de profesionales
- ✅ **Usuarios:** Administración de cuentas
- ✅ **Configuraciones:** Personalización del sistema
- ✅ **Reportes:** Estadísticas y métricas
- ✅ **Logs:** Auditoría de seguridad

### Estadísticas del Dashboard
- Total de reservaciones
- Pendientes de confirmar
- Completadas
- Canceladas
- Ingresos totales
- Ingresos promedio

## ⚙️ Módulo de Configuraciones

### Categorías de Configuración

#### General
- Nombre del sitio
- Logo del sitio
- Email de contacto
- Teléfono
- Dirección
- Descripción

#### Email / SMTP
- Host SMTP
- Puerto SMTP
- Usuario SMTP
- Contraseña SMTP

#### WhatsApp
- Número de WhatsApp
- WhatsApp habilitado/deshabilitado

#### Integraciones API
- PayPal Client ID
- PayPal Secret
- API Key para QR
- URL API HikVision
- API Key Shelly Relay

#### Colores del Sistema
- Color primario
- Color secundario

### Funcionalidades del Módulo
- ✅ Auto-guardado al cambiar valores
- ✅ Indicadores visuales de guardado
- ✅ Agrupación por categoría
- ✅ Tipos de datos (string, number, boolean)
- ✅ Solo accesible para Superadmin

## 📈 Reportes y Estadísticas

### Filtros de Reportes
- Rango de fechas personalizado
- Filtrado por sucursal
- Filtrado por especialista
- Filtrado por estado

### Métricas Disponibles
- Total de reservaciones
- Distribución por estado
- Ingresos totales
- Ingreso promedio
- Reservaciones por período
- Tendencias

### Visualizaciones
- ✅ Gráficos de barras
- ✅ Indicadores numéricos
- ✅ Tablas detalladas
- ✅ Porcentajes
- ✅ Comparativas

### Exportación
- ✅ Impresión de reportes
- ✅ Listado detallado

## 🔍 Logs de Seguridad

### Eventos Registrados
- Login exitoso
- Logout
- Login fallido
- Registro de nuevo usuario
- Cuenta bloqueada
- Reservación creada
- Reservación cancelada
- Reservación actualizada
- Configuración actualizada
- Cambio de contraseña

### Información del Log
- Fecha y hora exacta
- Tipo de evento
- Usuario responsable
- Descripción detallada
- Dirección IP
- User Agent

### Funcionalidades
- ✅ Paginación (50 registros por página)
- ✅ Ordenamiento cronológico
- ✅ Códigos de color por tipo
- ✅ Búsqueda por usuario
- ✅ Solo accesible para Superadmin

## 🎨 Interfaz de Usuario

### Diseño
- ✅ Tailwind CSS 3.x
- ✅ Diseño responsivo (móvil, tablet, desktop)
- ✅ Iconos Font Awesome 6.x
- ✅ Colores personalizables
- ✅ Animaciones suaves
- ✅ Mensajes flash (éxito/error)

### Navegación
- ✅ Menú principal dinámico
- ✅ Menú de usuario con dropdown
- ✅ Breadcrumbs
- ✅ URLs limpias y amigables
- ✅ Enlaces contextuales

### Componentes
- Tarjetas (cards)
- Tablas responsivas
- Formularios estilizados
- Botones con estados
- Badges de estado
- Modales (próximamente)
- Alertas
- Tooltips

## 📱 Características Técnicas

### Arquitectura
- **Patrón:** MVC (Model-View-Controller)
- **Frontend:** PHP sin frameworks
- **Base de Datos:** MySQL con PDO
- **Servidor:** Apache con mod_rewrite
- **Estilos:** Tailwind CSS CDN
- **JavaScript:** Vanilla JS

### Estructura de Archivos
```
reserbot/
├── app/
│   ├── controllers/      # Lógica de negocio
│   ├── models/          # Acceso a datos
│   └── views/           # Presentación
├── config/              # Configuración
├── public/              # Recursos públicos
├── logs/                # Logs del sistema
├── .htaccess           # Reglas Apache
├── index.php           # Front controller
└── database.sql        # Schema + datos
```

### Base de Datos

#### Tablas Principales
1. **usuarios** - Usuarios del sistema
2. **sucursales** - Ubicaciones
3. **categorias_servicio** - Categorías
4. **servicios** - Servicios disponibles
5. **especialistas** - Profesionales
6. **especialista_servicios** - Relación N:M
7. **horarios_especialista** - Horarios
8. **bloqueos_horario** - Vacaciones/bloqueos
9. **reservaciones** - Citas
10. **calificaciones** - Reseñas
11. **dias_festivos** - Días no laborables
12. **configuraciones** - Settings del sistema
13. **logs_seguridad** - Auditoría
14. **admin_sucursales** - Relación admin-sucursal

### Datos de Ejemplo
- ✅ 10 usuarios (todos los roles)
- ✅ 3 sucursales en Querétaro
- ✅ 6 categorías de servicio
- ✅ 10 servicios
- ✅ 4 especialistas
- ✅ Horarios configurados
- ✅ Reservaciones de muestra
- ✅ Días festivos de México
- ✅ 19 configuraciones del sistema

## 🔧 Herramientas de Testing

### test_connection.php
Verifica:
- ✅ Versión de PHP
- ✅ URL base detectada
- ✅ Estructura de directorios
- ✅ Permisos de escritura
- ✅ Extensión PDO
- ✅ Conexión a BD
- ✅ Existencia de tablas

### test_configuraciones.php
Verifica:
- ✅ Conexión a BD
- ✅ Tabla configuraciones
- ✅ Cantidad de configs
- ✅ Modelo Configuracion
- ✅ Lectura de valores
- ✅ AdminController
- ✅ Vista de configs
- ✅ Lista todas las configs por categoría

## 📚 Documentación

### Archivos de Documentación
- **README.md** - Documentación completa (11,000+ palabras)
- **INSTALL.md** - Guía de instalación rápida
- **FEATURES.md** - Este archivo
- **Comentarios en código** - Documentación inline

### Temas Cubiertos
- Instalación paso a paso
- Configuración
- Uso del sistema por rol
- Solución de problemas
- Estructura del proyecto
- Credenciales de prueba
- Roadmap futuro
- Contribuciones

## 🚀 Casos de Uso

### Cliente
1. Se registra en el sistema
2. Inicia sesión
3. Selecciona sucursal y servicio
4. Elige especialista
5. Selecciona fecha y hora disponible
6. Confirma reservación
7. Recibe confirmación

### Especialista
1. Inicia sesión
2. Ve citas del día en dashboard
3. Confirma citas pendientes
4. Completa citas realizadas
5. Ve su calificación promedio

### Administrador
1. Inicia sesión como admin
2. Ve estadísticas de sucursal
3. Gestiona reservaciones
4. Confirma citas pendientes
5. Genera reportes

### Superadministrador
1. Inicia sesión
2. Ve estadísticas globales
3. Gestiona sucursales
4. Configura el sistema
5. Revisa logs de seguridad
6. Genera reportes completos

## 🎯 Ventajas del Sistema

### Para Negocios
- ✅ Reducción de no-shows
- ✅ Mejor organización
- ✅ Trazabilidad completa
- ✅ Reportes automáticos
- ✅ Múltiples ubicaciones
- ✅ Escalable

### Para Clientes
- ✅ Reserva 24/7
- ✅ Ver disponibilidad real
- ✅ Confirmación inmediata
- ✅ Gestión de citas online
- ✅ Historial completo

### Para Especialistas
- ✅ Agenda digital
- ✅ Notificaciones (próximamente)
- ✅ Gestión de horarios
- ✅ Sistema de calificaciones
- ✅ Dashboard personal

## 🔮 Roadmap Futuro

### Próximas Funcionalidades
- [ ] Notificaciones por email
- [ ] Notificaciones por WhatsApp
- [ ] Integración con calendario (Google, Outlook)
- [ ] Recordatorios automáticos
- [ ] Pagos online con PayPal
- [ ] Generación de QR para citas
- [ ] Integración HikVision
- [ ] Integración Shelly Relay
- [ ] API REST
- [ ] Aplicación móvil
- [ ] Sistema de membresías
- [ ] Paquetes de servicios
- [ ] Descuentos y promociones

## 📄 Licencia

Proyecto de código abierto bajo licencia MIT.

---

**ReserBot** - Sistema Profesional de Reservaciones y Citas
Versión 1.0.0 | Desarrollado con ❤️ en PHP
