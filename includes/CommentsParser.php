<?php
/**
 * 
 * Clase que encapsula la l�gica que se le agregar� al parser de MediaWiki.
 * @author miguelerm
 *
 */
class CommentsParser{
	
	/**
	 * 
	 * Registra las funciones que deben ejecutarse al encontrar las palabras reservadas por la extension WikiComments.
	 * @return boolean
	 */
	static function FirstCallInit(){
		
		global $wgParser;
		
		$commentsFunction = new CommentsFunctions();
		$wgParser->setFunctionHook('commentsform', array( &$commentsFunction, 'renderForm' ));
		$wgParser->setFunctionHook('commentslist', array( &$commentsFunction, 'renderList'));
		
		//CheckDatabase verifica si las tablas requeridas se encuentran en la base
		//de datos, de lo contrario las crea.
		CommentsDB::CheckDatabase();
		
		return true;
		
	}
	
}