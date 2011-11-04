<?php
/*
 * 
 * Archivo de internacionalizacion para la extensión WikiComments.
 * 
 * @file
 * @ingroup Extensions
 * 
 */

$messages = array();



/** Español **/
$messages['es'] = array(

	/* Nombre del rol de administradores de comentarios */
	'commentadministrator' => 'Administrador de comentarios',
	
	/* Nombre que agrupa las páginas especiales de la extensión */
	'specialpages-group-comments' => 'Comentarios',
	
	/* Nombre de la página especial NewComment */
	'newcomment' => 'Nuevo Comentario',
	
	/* Nombre de la página especial CommentsAdministration */
	'commentsadministration' => 'Administrar Comentarios',

	/* Descripción que se muestra en los creditos de la extensión */
	'comentarios-desc' => 'permite que un usuario autenticado, coloque un comentario en un art&iacute;culo',

	/* Mensaje que se muestra al inicio de la lista de comentarios */

	'commentlist-message' => 'Comentarios',

	/* Formato de fecha */
	'commentlist-dateformat' => 'd/m/Y',
	
	/* Lista de comentarios vacía */
	'commentlist-empty' => 'No existe ning&uacute;n comentario para este art&iacute;culo.',
	
	/* Mensaje del formulario de nuevo comentario */
	'commentform-message' => 'Comparta su comentario',
	
	/* Etiqueta para el nombre del usuario */
	'username-label' => 'Nombre',
	
	/* Etiqueta para el texto del mensaje */
	'message-label' => 'Comentario',
	
	/* Texto para el boton 'Agregar comentario' del formulario de nuevo comentario */
	'addcomment-button' => 'Agregar comentario',
	
	/* Título de la página que agrega un nuevo comentario */
	'newcomment-title'   => 'Comentario Agregado',
	
	/* Título de la página que agrega un comentario si ocurre un error */
	'newcomment-errortitle' => 'Error',
	
	/* Mensaje que se muestra despues de que un usuario agrega un nuevo comentario */
	'newcomment-Message' => '=Su mensaje se ha guardado correctamente=
En el transcurso de 24 horas su mensaje sera aprobado para que pueda visualizarse en el articulo.
==Contenido del comentario:==
$1
Ahora puede regresar al articulo [[$2]]',
	
	/* Mensaje que se muestra si ha ocurrido un error al momento de agregar un comentario */
	'newcomment-error'   => '=Ocurrio un error al guardar el articulo=
Por favor intentelo nuevamente y si el problema persiste contacte al administrador.',

	/* Mensaje que se muestra cuando no se indica un id de articulo o el id indicado no es válido. */
	'invalid-article' => 'El articulo indicado no es valido.',
	
	/* Mensaje que se muestra cuando no se han especificado todos los argumentos esperados para realizar una tarea */
	'invalid-args' => 'Los argumentos indicados son invalidos.',
	
	/* Título de la página que muestra los comentarios pendientes de aprobación */
	'comments-title' => 'Comentarios'
);