JARED ELIEZER CABRERA CATALAN#21
INSTITUTO NACIONAL CANTON LOURDES 
PRACTICA FINAL
PROF JAVIER MINEROS

# Sistema de Inscripción de Becas - PHP + MySQL y WindowsForms

## Descripción

Este proyecto consiste en un sistema web para registrar aspirantes a una beca utilizando PHP, MySQL y PDO. El formulario permite ingresar la información del estudiante, validarla y almacenarla de forma segura en la base de datos.

---

# Tecnologías utilizadas

- PHP 8
- MySQL
- PDO
- HTML5
- CSS3
- XAMPP
- Visual Studio Code

---

# Requisitos

- XAMPP instalado
- Apache iniciado
- MySQL iniciado
- Visual Studio Code

---

# Instalación

## 1. Copiar el proyecto

Copiar la carpeta del proyecto dentro de:

```
htdocs/
```

Ejemplo:

```
C:\xampp\htdocs\SisBecasWeb
```

---

## 2. Crear la base de datos

Abrir phpMyAdmin.

Crear la base de datos:

```
sisbecas_web
```

Ejecutar el archivo:

```
schema.sql
```

Esto creará todas las tablas y los datos iniciales.

---

## 3. Configurar la conexión

Abrir:

```
config/config.php
```

Modificar únicamente si cambia el usuario o contraseña de MySQL.

---

## 4. Ejecutar

Abrir el navegador.

Entrar a:

```
http://localhost/SisBecasWeb/
```

o

```
http://localhost/SisBecasWeb/inscripcion.php
```

---

# Estructura del proyecto

```
SisBecasWeb/

config/
    config.php

css/
    estilos.css

includes/

inscripcion.php

gracias.php

schema.sql

README.md
```

---

# Pasos lógicos del desarrollo

## Paso 1

Crear la base de datos.

Se diseñaron las tablas necesarias para almacenar:

- Tipos de beca.
- Aspirantes.

También se agregaron las llaves primarias, foráneas y la restricción UNIQUE para evitar DUI repetidos.

---

## Paso 2

Realizar la conexión con MySQL.

Se creó el archivo:

```
config.php
```

Este archivo centraliza la conexión utilizando PDO.

La ventaja es que, si cambia el servidor, solamente se modifica un archivo.

---

## Paso 3

Consultar los tipos de beca.

Al abrir el formulario se ejecuta un SELECT para llenar automáticamente el elemento `<select>`.

Esto evita escribir manualmente las opciones en el HTML.

---

## Paso 4

Diseñar el formulario.

Se construyó un formulario con HTML5 y CSS.

Se utilizaron:

- input
- select
- radio button
- botones

para capturar toda la información del aspirante.

---

## Paso 5

Validar la información.

Antes de guardar los datos se verifican:

- Formato del DUI.
- Teléfono.
- Correo electrónico.
- Promedio.
- Edad.
- Institución.
- Tipo de beca.

Esto evita almacenar información incorrecta.

---

## Paso 6

Guardar la información.

Cuando todas las validaciones son correctas se utiliza PDO con consultas preparadas.

Se ejecuta un INSERT utilizando parámetros.

Esto evita ataques de inyección SQL.

---

## Paso 7

Controlar errores.

Si el DUI ya existe en la base de datos se captura la excepción y se muestra un mensaje amigable al usuario.

La aplicación no se detiene.

---

## Paso 8

Redireccionar.

Después de guardar correctamente la información se redirecciona a:

```
gracias.php
```

Mostrando el número de inscripción.

---

# Funcionamiento general

1. El usuario abre el formulario.
2. El sistema carga los tipos de beca.
3. El usuario llena todos los datos.
4. PHP valida la información.
5. Si existe un error se muestran los mensajes.
6. Si todo es correcto se guarda en MySQL.
7. Se muestra la pantalla de confirmación.

---

# Seguridad implementada

✔ Validaciones del lado del cliente.

✔ Validaciones del lado del servidor.

✔ Consultas preparadas con PDO.

✔ Protección contra inyección SQL.

✔ Restricción UNIQUE para DUI.

✔ Escape de datos mediante htmlspecialchars().

---

# Aprendizajes obtenidos

Durante este proyecto se practicó:

- Conexión PHP con MySQL.
- Uso de PDO.
- Consultas preparadas.
- Validación de formularios.
- Manejo de excepciones.
- Organización de proyectos web.
- Uso de HTML5 y CSS3.
- Programación segura contra inyección SQL.

---




# Sistema de Gestión de Becas - Windows Forms + SQL Server

## Descripción

Aplicación de escritorio desarrollada en C# utilizando Windows Forms y SQL Server. Permite registrar aspirantes a becas mediante un formulario con validaciones, conexión a base de datos y almacenamiento seguro de la información.

---

# Tecnologías utilizadas

- C#
- Windows Forms (.NET)
- SQL Server
- ADO.NET
- App.config
- Visual Studio 2022

---

# Requisitos

- Visual Studio 2022
- SQL Server o LocalDB
- .NET instalado

---

# Instalación

## 1. Abrir el proyecto

Abrir el archivo:

```
SisBecasWinForms.sln
```

---

## 2. Crear la base de datos

Abrir SQL Server Management Studio.

Ejecutar el archivo:

```
Script.sql
```

Este script crea:

- Base de datos.
- Tablas.
- Llaves primarias.
- Llaves foráneas.
- Restricciones.
- Datos iniciales.

---

## 3. Configurar la conexión

Abrir:

```
App.config
```

Modificar únicamente la cadena de conexión si cambia el servidor SQL.

Ejemplo:

```xml
Data Source=(localdb)\MSSQLLocalDB;
Initial Catalog=SisBecas_DB;
Integrated Security=True;
```

---

## 4. Ejecutar

Presionar:

```
F5
```

o

```
Iniciar Depuración
```

---

# Estructura del proyecto

```
SisBecasWinForms

Datos/
    Conexion.cs
    TipoBeca.cs

frmAspirantes.cs

App.config

Program.cs
```

---

# Pasos lógicos del desarrollo

## Paso 1

Diseñar la base de datos.

Se crearon las tablas:

- Tipos_Beca
- Aspirantes

También se agregaron:

- Llaves primarias.
- Llaves foráneas.
- Restricción UNIQUE para el DUI.

Esto garantiza la integridad de la información.

---

## Paso 2

Crear el formulario.

Se agregaron los controles necesarios:

- TextBox
- ComboBox
- DateTimePicker
- NumericUpDown
- RadioButton
- Buttons
- MenuStrip
- ToolStrip
- StatusStrip

Con esto se construyó la interfaz donde el usuario ingresa los datos.

---

## Paso 3

Conectar con SQL Server.

Se creó una clase llamada:

```
Conexion.cs
```

Esta clase obtiene la cadena de conexión desde:

```
App.config
```

La ventaja es que, si cambia el servidor, únicamente se modifica el archivo de configuración.

---

## Paso 4

Probar la conexión.

Al iniciar el formulario se intenta abrir la conexión con SQL Server.

Si la conexión es correcta se muestra el estado:

```
Conectado
```

Si ocurre un error se informa al usuario mediante un MessageBox.

---

## Paso 5

Cargar los tipos de beca.

Al abrir el formulario se ejecuta un SELECT sobre la tabla:

```
Tipos_Beca
```

Los registros obtenidos se cargan automáticamente en el ComboBox para que el usuario pueda seleccionar una beca.

---

## Paso 6

Validar la información.

Antes de guardar se verifica:

- DUI con formato correcto.
- Nombres obligatorios.
- Apellidos obligatorios.
- Sexo seleccionado.
- Teléfono válido.
- Correo electrónico válido.
- Edad entre 15 y 30 años.
- Promedio entre 6 y 10.
- Ingreso familiar.
- Tipo de beca seleccionado.

Estas validaciones evitan almacenar datos incorrectos.

---

## Paso 7

Guardar el aspirante.

Cuando todas las validaciones son correctas se ejecuta un INSERT utilizando SqlCommand y parámetros.

Esto evita ataques de inyección SQL y garantiza un almacenamiento seguro.

---

## Paso 8

Controlar errores.

Si el DUI ya existe en la base de datos se captura la excepción y se muestra un mensaje indicando que el registro ya existe.

La aplicación continúa funcionando sin cerrarse.

---

## Paso 9

Limpiar el formulario.

Después de guardar correctamente la información se limpian todos los controles para permitir un nuevo registro.

---

## Funcionamiento general

1. El usuario abre la aplicación.
2. El sistema verifica la conexión con SQL Server.
3. Se cargan automáticamente los tipos de beca.
4. El usuario llena el formulario.
5. El sistema valida los datos.
6. Si existe algún error se muestran mensajes.
7. Si todo es correcto se guarda el registro.
8. Se muestra un mensaje de confirmación y el formulario queda listo para un nuevo registro.

---

# Seguridad implementada

✔ Validaciones del formulario.

✔ Uso de SqlCommand con parámetros.

✔ Restricción UNIQUE para el DUI.

✔ Manejo de excepciones.

✔ Separación de la conexión mediante App.config.

---

# Aprendizajes obtenidos

Durante este proyecto se practicó:

- Creación de interfaces con Windows Forms.
- Conexión con SQL Server.
- Uso de ADO.NET.
- Manejo de App.config.
- Consultas SQL.
- Validación de datos.
- Manejo de excepciones.
- Organización del código en clases.
- Programación segura mediante consultas parametrizadas.

---

# Explicación rápida del flujo del programa

1. Se abre el formulario.
2. Se conecta a SQL Server.
3. Se cargan los tipos de beca.
4. El usuario ingresa la información.
5. Se validan los datos.
6. Se ejecuta el INSERT en la base de datos.
7. Se muestra un mensaje de éxito.
8. Se limpian los controles para un nuevo registro.

---
