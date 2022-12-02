# AsoGest

![License](https://img.shields.io/badge/license-MIT-success)
![Release](https://img.shields.io/badge/release-Beta-orange)
![Laravel](https://img.shields.io/badge/Laravel-9.40.1-blue)
![PHP](https://img.shields.io/badge/PHP-8.0.25-blue)

AsoGest fue creado inicialmente para la asociación de juegos de mesa, rol y wargames a la que estuve asociado. La web fue creada en 2019, y desde entonces la he implementado y mejorado. Lleva más de dos años en funcionamiento en la asociación.

Para desarrollar el proyecto he usado el [framework Laravel](https://github.com/laravel/laravel) por su robustez y facilidad de uso, además de por tener una curva de aprendizaje sencilla. 

AsoGest sirve para gestionar todos los aspectos administrativos relacionados con una Asociación Cultural, como pueden ser asuntos de secretaría, gestión de cuotas, tesorería, administración de socios, comunicación, etc.


![Pantalla principal de Asogest](./public/images/welcome.png) 


### Pre-requisitos 📋

Para la instalación de un proyecto en un entorno de pruebas necesitamos una máquina virtual XAMPP (o similar). Puedes leer las ![Instrucciones de Instalación](./storage/app/public/documentos/Instrucciones AsoGest.pdf) para más detalles.


### Instalación 🔧

Aquí está contenido todo el código fuente del proyecto en Laravel. Pero se puede instalar una copia completamente funcional en un servidor local XAMPP o en un servidor de internet.
Para instalar una copia del programa Asogest y ejecutarla en local se deben seguir siguientes pasos, adaptándolos al entorno que se haya escogido. En general deberemos:

1. Crear un servidor local con XAMPP
2. Subir la carpera asogest a la carpeta HTDOCS de XAMPP
3. Abrir la interfaz de MySQL y crear una nueva base de datos llamada "asogest" (o cualquier otro nombre)
4. Abrir una consola de comandos en la raiz de la carpeta del programa y ejecutar: php artisan key:generate
5. Ejecutar en la misma consola de comandos: php artisan migrate:fresh --seed
6. (si es necesario) Modificar los HTACCESS de la carpeta raiz y de la carpeta public para gestionar los redireccionamientos correspondientes del TOMCAT. (El de la carpeta Public, si hemos creado bien el de la raiz, no hace falta tocarlo, es el que trae por defecto Laravel y funciona bien tal y como está)
7. Modificar el .env de la carpeta raiz para reflejar los cambios correspondientes a la conexión con la base de datos y el entorno de desarrollo.

Para más información y una guía paso a paso, puedes leer las [Instrucciones de Instalación](Instrucciones_Instalación_AsoGest.pdf)

## CAPTURAS 📷

<img src="./capturas/Home.png" width="100"> <img src="./capturas/Tesoreria.png" width="100"> <img src="./capturas/Asoc1.png" width="100"> <img src="./capturas/Asoc2-Ludo.png" width="100"> <img src="./capturas/Asoc3.png" width="100"> <img src="./capturas/conf.png" width="100"> <img src="./capturas/Documentos.png" width="100"> <img src="./capturas/Secretario.png" width="100"> <img src="./capturas/FichaSocio.png" width="100"> <img src="./capturas/gestvoc.png" width="100"> <img src="./capturas/Vocal1.png" width="100"> <img src="./capturas/voc2.png" width="100"> <img src="./capturas/voc3.png" width="100"> <img src="./capturas/voc4.png" width="100"> <img src="./capturas/voc5.png" width="100">

## ¿QUIERES PROBARLO? 👍 

He creado una replica en una web. Es casi completamente operativa. Puedes probar casi todas las funciones y ver como se vería en un entorno real.
Los datos contenidos son ficticios y se resetearám cada cierto tiempo.

Para ir, pincha en este enlace: [https://asogest.com.es](https://asogest.com.es)

Puedes jugar con algunos de estos usuarios:

| USUARIO      | CONTRASEÑA |
| ----------- | ----------- |
| admin      | admin1       |
| tesorero   | tesorero1        |
| secretario   | secretario1        |
| usuario4   | usuario1234        |
| usuario5   | usuario1234        |
| usuario6   | usuario1234        |

Hay usuarios del 4 al 15 para poder jugar con ellos. Todos tienen la misma contraseña.
Por favor, comunícame cualquier incidencia que encuentres.

## Que se ha implementado hasta ahora ⚙️
- Gestión de la Secretaría de la asociación:
    - Vista de Secretario con la gestión completa de socios (creación, edición, etc).
    - Visor y gestión de las invitaciones del socio.
    - Habilitar y deshabilitar Socios
    - Información de acceso a Drive o carpetas compartidas (externas). Se puede cambiar por otras opciones, como gestión de llaves, etc., a través de dos variables personalizables (Acceso Drive y Acceso Junta).
    - Gestión de las vocalías (creación, eliminación y edición)
- Gestión de la Tesorería:
    - Panel de control para el tesorero con visión general del estado de la tesorería.
    - Gestión de apuntes contables (creación, edición) con ajuste al Plan General Contable (Criterio de Devengo).
    - Visionado de los apuntes contables en diferentes vistas con filtrado.
    - Gestión de Cuotas de los socios
- Creación de tipos de cuota, gestión y renovación de cuotas de socio.
- Avisos automáticos por email cuando está próximo el vencimiento de la cuota (hay que habilitar el Cron en el servidor)
- Visionado de las cuotas atrasadas, próximas renovaciones, etc.
- Moratoria de cuotas
- Exportación e Importación de los apuntes contables en Excel, copiados en portapapeles o impresión.
- Creación de certificado en PDF del coste de mantenimiento del local (Alquiler)
- Creación de informes personalizados de tesorería.
- Gestión de la ficha del socio
    - Datos personales (nombre, apellido, dirección, email, teléfono, etc.)
    - Gestión de eventos creados por el socio (eliminar los eventos y reservas realizadas) (necesaria cuenta de GMAIL)
    - Gestión por parte del socio de sus invitaciones
    - Preguntas sobre Privacidad y permisos del socio para comunicaciones.
    - Gestión de preferencias de Vocalías (muestra el interés del socio en esa vocalía. Se puede usar para destinar parte de la cuota a sus vocalías o intereses)
    - Gestión del rol del socio (tesorero, secretario, vocal, socio normal, etc…)
    - Gestión de eventos creados por el socio (necesaria cuenta de GMAIL).
- Gestión de Vocalías (Secciones o Grupo de Trabajo)
    - Cada vocalía actúa de manera independiente
    - Calendario independiente para cada vocalía (necesaria cuenta de GMAIL)
    - Panel del vocal donde se expone información personalizada
    - Gestión de propuestas de compra con un sistema de votaciones
    - Histórico de compras de esa vocalía
    - Gestión del presupuesto de cada vocalía
    - Envío de email del vocal a los socios interesados en la vocalía o a socios en concreto.
- Gestión de Eventos
    - Calendario de Google con sincronización (necesaria cuenta de GMAIL).
    - Creación de eventos por vocalía, generales o importantes (necesaria cuenta de GMAIL).
    - Visualización de los próximos eventos en la web (diferentes formatos)
- Emails:
    - Envíos de email entre socios (al crear un evento)
    - Envío de email Vocal-Socio (al hacer una compra, para hacer un comunicado de vocalía, etc.)
    - Envío de email de socio a junta directiva.
- Visor de Registros (ver las acciones que se han realizado en la web y quién las ha hecho)
- Gestor Documental (almacenamiento de documentos de la Asociación)

Al ser un proyecto realizado por mi únicamente (un solo programador), desde cero, espero que contenga bugs y fallos fácilmente solucionables. Además, una refactorización no le vendría mal. Tampoco he seguido ninguna metodología de programación. Se ha hecho por placer. 


## Construido con 🛠️

El proyecto ha sido desarrollado en **Laravel**. Se han usado las siguientes librerías y frameworks, con sus versiones, a parte de las que ya trae integradas Laravel:

| LIBRERÍAS | VERSIÓN |
| -- | -- |
| **LARAVEL** | 9.40.1 |
| bacon/bacon-qr-code | 2.0.7 | 
| balping/json-raw-encoder |v1.0.1 |
| barryvdh/laravel-dompdf |dev-master 7516caa |
| brick/math | 0.10.2 |
| consoletvs/charts | 6.5.6 |
| dasprid/enum | 1.0.3 |
| dompdf/dompdf | v2.0.1 |
| google/apiclient | v2.12.6 |
| google/apiclient-services | v0.274.0 |
| google/auth | v1.23.1 |
| intervention/image | 2.7.2 |
| jenssegers/agent | v2.6.4 |
| maatwebsite/excel | 3.1.44 |
| nesbot/carbon |2.63.0 |
| simplesoftwareio/simple-qrcode | 4.2.0 |
| spatie/laravel-google-calendar | 3.5.1 |
| yajra/laravel-datatables | v9.0.0 |
| yajra/laravel-datatables-buttons | v9.1.3 |
| yajra/laravel-datatables-editor | v1.25.1 |
| yajra/laravel-datatables-fractal | v9.1.0 |
| yajra/laravel-datatables-html | v9.3.4 |
| yajra/laravel-datatables-oracle | v10.2.0 |


## Contribuyendo 🖇️

Si quieres contribuir al proyecto con sugerencias puedes escribirme a mi email o a través de LinkedIn. O bien puedes hacer una rama del proyecto y modificarla como gustes. ¡Siéntete Libre!

## Ayuda 📖

En la sección de "Hacer un Tour" tendrás más información de cómo funciona el programa. 
ACTUALIZACION 2022-11-21: Actualmente los videos que hay son de una versión muy obsoleta y anticuada. Se están realizando nuevos videos informativos.


## Versionado 📌

Esta no es la primera versión del programa, ya que, después de revisiones, añadidos y mejoras tras varios años de testeo y producción en vivo, esta es la versión 1.4-beta. No se si se realizarán más o el proyecto finalizará aquí. Pero si haces una rama y mejoras algo, agradecería que me enviases los cambios para hacer una segunda versión mejorada.

## Autor ✒️

* **Alejandro Campos** - *Trabajo Inicial y desarrollo completo* - [linkedIn](https://www.linkedin.com/in/acamfue/)

## Licencia 📄

Este proyecto está bajo la Licencia misma de Laravel, la MIT - mira el archivo [LICENSE.md](LICENSE.md) para más detalles.

Esto te permite a cualquier persona que obtenga una copia de este software y de los archivos de documentación asociados (el "Software"), a utilizar el Software sin restricción, incluyendo sin limitación los derechos a usar, copiar, modificar, fusionar, publicar, distribuir, sublicenciar, y/o vender copias del Software, y a permitir a las personas a las que se les proporcione el Software a hacer lo mismo, sujeto a las siguientes condiciones:
- El aviso de copyright anterior y este aviso de permiso se incluirán en todas las copias o partes sustanciales del Software. 

## Gracias a 🎁

* Primero, gracias a mi Asociación, [Portal Lúdico](https://www.portalludico.com). Aunque ya no siga siendo socio activo, por cuestiones laborales y familiares, la llevo en mi corazón. También gracias a mi anterior Asociación, [Mecatol Rex](https://www.mecatolrex.com/), donde hice muy buenos amigos y disfruté jugando. Aprendí mucho de como gestionar una asociación y también les llevo en el corazón. 📢
* Por supuesto a mi familia, que me ha soportado después de tantas horas de programación en los ratos libres 🤓.
* Por último a toda la gente que crea cosas maravillosas y las comparte libre y gratuítamente.

## ¿Quieres invitarme a un café? ☕

Aunque no es necesario, agradeceré cualquier invitación. ¡A tu salud!
https://paypal.me/acamfue?country.x=ES&locale.x=es_ES
