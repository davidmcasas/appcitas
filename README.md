# Prueba Técnica para PandoraFMS
## Por David Martínez Casas

Se trata de una aplicación web realizada en Laravel 11 (PHP 8.3)

La aplicación se encuentra desplegada en: https://pruebapandora.davidmcasas.com

Nota: En el historial de GIT se puede ver el commit del ejercicio, para saber rápidamente que archivos pertenecen
de base a Laravel, y que archivos o cambios pertenecen al desarrollo del ejercicio.

## Partes del ejercicio:

### 1. Implementar modelo de datos en SQL.
En un principio iba hacerlo usando dos tablas, Paciente y Cita, pero surgían casos que el enunciado no contempla.
Por ejemplo: ¿Qué pasaría con los datos de un paciente si pide otra cita pero introduce un email y teléfono diferentes a la cita anterior?
Para no enredarme con eso y que sea lo más fiel a lo que pide el ejercicio, lo hago con una única tabla de Citas.

Ver archivo: [database/migrations/2024_11_08_084845_create_app_tables.php](database%2Fmigrations%2F2024_11_08_084845_create_app_tables.php)

### 2. Implementar formulario de recepción de datos

Vista del formulario: 
[views/appointments/new.blade.php](resources%2Fviews%2Fappointments%2Fnew.blade.php)

Vista de confirmación: 
[views/appointments/created.blade.php](resources%2Fviews%2Fappointments%2Fcreated.blade.php)

Validación de los campos del formulario del lado del servidor:
[AppointmentRequest.php](app%2FHttp%2FRequests%2FAppointmentRequest.php)

Controlador de citas:
[AppointmentController.php](app%2FHttp%2FControllers%2FAppointmentController.php)

El script del Ajax del DNI lo he embebido en la vista por agilizar la tarea,
aunque sería más correcto extraerlo a un .js aparte y compilarlo con Vite.

Para la validación frontend del email solo he puesto type="email" en el input,
en backend también se valida que sea un email válido mediante la regla de validación 'email', por lo que es imposible
ingresar un email inválido.

### 3. Implementar sistema de asignación de citas/horas
En AppointmentController.php está la función que asigna horas de forma automática.
Esta función está simplificada y cumple el enunciado del ejercicio, pero no es realista,
ya que no contempla casos como que se elimine una cita y se quede su hueco disponible (el ejercicio no pide contemplar la eliminación de citas).
Se asignan citas siempre a partir del día siguiente al actual.

También he implementado una comprobación para evitar citas con hora duplicada por peticiones concurrentes.
Al tratarse de inserción de registros nuevos, compruebo tras la inserción que no haya otro registro con la misma fecha.
Si se tratase de lectura o actualización de registros existentes, utilizaría un bloqueo de filas con lockForUpdate()

#### Actualización:
Tras realizar un stress test de concurrencia con JMeter (lanzando cientos de peticiones de citas simultáneas en múltiples hilos),
he subido una corrección de última hora (ver historial GIT).
De la forma que estaba originalmente, nunca iba a haber fechas duplicadas, pero podían quedarse huecos libres si dos citas
intentaban corregir su fecha a la vez. Con la corrección, no se quedarán huecos de horas libres.
Puede ocurrir que el orden de IDs en BBDD de las citas no se corresponda con su orden de fechas, pero esto es trivial.

Nota: el enunciado dice que se puede asignar citas desde las 10 hasta las 22, y que las citas duran 1 hora,
aquí me surge la duda de si la hora 22 también es asignable (la cita acabaría a las 23), he supuesto que no,
por tanto caben 12 citas en un día y la última hora asignable es las 21.

### 4. [OPCIONAL] Implementar envío por email al paciente con la cita.
Implementado mediante Notifications de Laravel. Las notificaciones serán encoladas en la tabla jobs,
por tanto solo se enviarán si hay un worker de Laravel ejecutando la cola "notifications".
Por seguridad, en mi servidor no se está ejecutando un worker, de forma que los emails se quedarán encolados sin llegarse a enviar nunca.

[Notifications/AppointmentCreated.php](app%2FNotifications%2FAppointmentCreated.php)

Nota: el HTML generado por la plantilla de notificación de Laravel contiene algunos textos en inglés,
se puede traducir y modificar al gusto publicando y modificando dicha plantilla, pero no me he parado a ello.

### [Adiccional] Vista de administración con listado de citas

Me he tomado la libertad de añadir una vista de administración con una tabla de citas muy rudimentaria.

Se puede acceder pulsando el botón "Acceso" e iniciando sesión con el usuario de BD de la tabla users, por defecto:
test@example.com | 1234

No me he parado a traducir ni modificar la vista de login de Laravel porque se sale del ejercicio.


## Pasos para desplegar en otro sistema: 

### 1. Clonar el repositorio al servidor donde se vaya a probar e instalar dependencias.
```
composer install
```
En caso de conflicto por tener varias versiones de composer, utilizar:
```
composer2 install
```

### 2. Copiar ".env.example" y renombrarlo a ".env", y configurarlo.

En el archivo .env es necesario configurar las variables de entorno de la base de datos que se vaya a utilizar.
Para pruebas rápidas se puede utilizar SQLite (si el servidor lo permite) "DB_CONNECTION=sqlite".
Para MySQL: "DB_CONNECTION=mysql" y configurar el resto de variables DB_*

### 3. Generar clave hash de la aplicación.
```
php artisan key:generate
```
Laravel escribirá esta clave en el fichero .env en "APP_KEY"

### 4. Crear las tablas de la BBDD y ejecutar el Seeder.
```
php artisan migrate:fresh --seed
```
El seeder pedirá introducir la contraseña deseada para los usuarios de ejemplo.
De no introducirla, se usará por defecto "1234".

### 5. Compilar los CSS y JS de Vite

```
npm run build
```
Esto creara la carpeta public/build con los .js y .css compilados de la aplicación

Probado con Node 23

### 6. (Opcional) Ejecutar cacheado de Laravel
```
php artisan optimize
``` 

### 7. (Opcional) Configurar envío de email

En el .env, las variables MAIL_* están configuradas por defecto
para que los emails se registren en el Log de laravel en lugar de enviarse
("MAIL_MAILER=log"). Para que se envíen por smtp, hay que poner
"MAIL_MAILER=smtp" y el configurar el resto de variables MAIL_*.

Recordatorio: los emails solo se ejecutarán si hay un worker de Laravel ejecutándose para la cola de notificaciones,
de lo contrario permanecerán encolados en la tabla jobs
```
php artisan queue:work --queue=notifications
```

