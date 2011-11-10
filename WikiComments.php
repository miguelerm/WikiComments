<?php
/*
 * WikiComments - Extension que permite que un usuario autenticado, coloque un 
 * comentario en algun artículo wiki.
 * 
 * Para instalar esta extension, agregue en el archivo LocalSettings.php
 * la línea: require_once('$IP/extensions/WikiComments/WikiComments.php')
 * 
 * @file
 * @ingroup Extensions
 * @version 0.2
 * @author Miguel Roman <miguelerm@gmail.com>
 * 
 */

if(!defined('MEDIAWIKI')) {
	echo("Esta extension de MediaWiki no puede ejecutarse independientemente.\n");
	die(-1);
}

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'WikiComments',
	'author'         => 'Miguel Roman <miguelerm@gmail.com>',
	'url'            => 'https://github.com/miguelerm/wikicomments',
	'version'        => '0.2',
	'descriptionmsg' => 'comentarios-desc',
	'description'    => 'Just Another Comments Extension for MediaWiki'
);

require_once 'WikiComments.body.php';
