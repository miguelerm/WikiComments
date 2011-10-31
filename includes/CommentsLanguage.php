<?php
class CommentsLanguage{
	
	static function GetMagic(&$magicWords, $langCode){
		switch ( $langCode ) {
			default:
			$magicWords[ 'commentsform' ] = array( 0, 'commentsform' );
			$magicWords[ 'commentslist' ] = array( 0, 'commentslist' );
		}
		return true;
	}
}