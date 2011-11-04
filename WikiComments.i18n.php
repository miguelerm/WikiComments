<?php
/*
 * 
 * Archivo de internacionalizacion para la extensi�n WikiComments.
 * 
 * @file
 * @ingroup Extensions
 * 
 */

$messages = array();



/** Espa�ol **/
$messages['es'] = array(

	/* Nombre del rol de administradores de comentarios */
	'commentadministrator' => 'Administrador de comentarios',
	
	/* Nombre que agrupa las p�ginas especiales de la extensi�n */
	'specialpages-group-comments' => 'Comentarios',
	
	/* Nombre de la p�gina especial NewComment */
	'newcomment' => 'Nuevo Comentario',
	
	/* Nombre de la p�gina especial CommentsAdministration */
	'commentsadministration' => 'Administrar Comentarios',

	/* Descripci�n que se muestra en los creditos de la extensi�n */
	'comentarios-desc' => 'permite que un usuario autenticado, coloque un comentario en un art&iacute;culo',

	/* Mensaje que se muestra al inicio de la lista de comentarios */

	'commentlist-message' => 'Comentarios',

	/* Formato de fecha */
	'commentlist-dateformat' => 'd/m/Y',
	
	/* Lista de comentarios vac�a */
	'commentlist-empty' => 'No existe ning&uacute;n comentario para este art&iacute;culo.',
	
	/* Mensaje del formulario de nuevo comentario */
	'commentform-message' => 'Comparta su comentario',
	
	/* Etiqueta para el nombre del usuario */
	'username-label' => 'Nombre',
	
	/* Etiqueta para el texto del mensaje */
	'message-label' => 'Comentario',
	
	/* Texto para el boton 'Agregar comentario' del formulario de nuevo comentario */
	'addcomment-button' => 'Agregar comentario',
	
	/* T�tulo de la p�gina que agrega un nuevo comentario */
	'newcomment-title'   => 'Comentario Agregado',
	
	/* T�tulo de la p�gina que agrega un comentario si ocurre un error */
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

	/* Mensaje que se muestra cuando no se indica un id de articulo o el id indicado no es v�lido. */
	'invalid-article' => 'El articulo indicado no es valido.',
	
	/* Mensaje que se muestra cuando no se han especificado todos los argumentos esperados para realizar una tarea */
	'invalid-args' => 'Los argumentos indicados son invalidos.',
	
	/* T�tulo de la p�gina que muestra los comentarios pendientes de aprobaci�n */
	'comments-title' => 'Comentarios'
);