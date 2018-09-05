# worklife

# Pasos instalacion:

1 - Primero bajar todo el repositorio en alguna carpeta.

2 - Segundo, bajar xaamp (https://www.apachefriends.org/es/index.html). De ya tenerlo o tner algun otro programa que corra apache. Solo queda configurarlo.

3 - Configurar Xaamp: Tenemos que hacer que nuestro localhost, sea la carpeta de github de worklife. Por ejemplo si nuestra carpeta de github de worklife: es "C:/github/worklife" entonces tenemos que ir al archivo httpd.conf del xaamp y cambiar la linea: DocumentRoot "C:\Github\worklife". Con eso le indicamos que el localhost apunta a la carpeta de worklife.

4 - Hoy en día la página no esta funcionando sin tener instalada la BD. Entonces hay que instalarla. Con el xaamp mismo podemos activar el modulo MYSQL con lo cual solo faltaría crear la base de datos e importar los datos.

5 - Para crear la base de datos, primero tenemos que acceder a http://localhost/phpmyadmin/. Una vez acá, hay que ir a la solapa "Base de datos". Allí encontraran un text box donde se puede ingresar el nombre de la base de datos: "inventa_worklife" y el cotejamiento. En el cotejamiento seleccionen "utf8_general_ci" y presionen el boton CREAR. Listo, base de datos creada, solo falta cargar los datos.

6 - Luego para cargar todas las tablas y datos en la base de datos, tienen que correr el SQL adjunto en la carpeta /admin/sql/inventa_worklife
