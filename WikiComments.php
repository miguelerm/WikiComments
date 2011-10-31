<?php
/*
 * Comentarios - Extension que permite que un usuario autenticado, coloque un 
 * comentario en algun artículo que tenga la funcion {{#commentsform:}}.
 * 
 * y permite visualizar la lista de comentarios si el artículo tiene la funcion
 * {{#commentslist:}}
 *  
 * Para instalar esta extension, agregue en el archivo LocalSettings.php
 * la línea: require_once('$IP/extensions/WikiComments/WikiComments.php')
 * 
 * @file
 * @ingroup Extensions
 * @version 0.1
 * @author Miguel Roman <miguelerm@gmail.com>
 * 
 */

if(!defined('MEDIAWIKI')) {
	echo("Esta extension de MediaWiki no puede ejecutarse independientemente.\n");
	die(-1);
}

$wgExtensionCredits['parserhook'][] = array(
	'path'           => __FILE__,
	'name'           => 'WikiComments',
	'author'         => 'Miguel Roman <miguelerm@gmail.com>',
	'url'            => 'https://github.com/miguelerm',
	'version'        => '0.1',
	'descriptionmsg' => 'comentarios-desc',
	'description'    => 'Just Another Comments Module for MediaWiki'
);

require_once 'WikiComments.body.php';

