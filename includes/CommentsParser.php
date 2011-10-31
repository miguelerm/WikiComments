<?php
class CommentsParser{
	
	static function FirstCallInit(){
		
		global $wgParser;
		
		$commentsFunction = new CommentsFunctions();
		$wgParser->setFunctionHook('commentsform', array( &$commentsFunction, 'renderForm' ));
		$wgParser->setFunctionHook('commentslist', array( &$commentsFunction, 'renderList' ));
		
		return true;
		
	}
	
}