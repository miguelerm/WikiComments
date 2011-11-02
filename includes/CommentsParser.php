<?php
class CommentsParser{
	
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