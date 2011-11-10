WikiComments
============

Una extension mas que permite agregar comentarios a un aticulo wiki.


Caracteristicas
---------------

- Los comentarios se persisten en la base de datos en una tabla exclusiva para 
  ellos. 
  > A diferencia de la extension [CommentBox][1] que persiste los comentarios 
    como contenido del articulo.
  
- Los comentarios deben ser aprobados por algun usuario que se encuentre en el 
  rol "commentadmin" para que sean visibles en la pagina del articulo.

- Solamente los usuarios autenticados pueden crear nuevos comentarios.

- Los comentarios pueden ser respondidos, y las respuestas se visualizan como
  una sub-lista del comentario padre.

- Creacion automatica de las tablas requeridas en la base de datos.


Estado del proyecto
-------------------

Proyecto en estado Alfa, trabajando en las siguientes caracteristicas:

- Implementar una hoja de estilos css base.

- Permitir desabilitar comentarios en base a Namespaces o Articulos en 
  particular 


Instalacion
-----------

Para instalar la extension, solamente agregue la siguiente linea al archivo 
[LocalSettings.php][2] que se encuentra en la carpeta en la que se encuentra
instalado MediaWiki:

    require_once('$IP/extensions/WikiComments/WikiComments.php')

> $IP es la carpeta raiz en donde está instalado MediaWiki.


[1]: http://www.mediawiki.org/wiki/Extension:Commentbox "Documentacion de la extension CommentBox"
[2]: http://www.mediawiki.org/wiki/Manual:LocalSettings.php "Manual del archivo LocalSettings.php"