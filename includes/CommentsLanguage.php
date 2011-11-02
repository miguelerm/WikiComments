<?php

/**
 * 
 * Clase que encapsula las funciones que se ven afectadas segun el idioma en el que se encuentre MediaWiki.
 * @author miguelerm
 *
 */
class CommentsLanguage{

	/**
	 * 
	 * Registra las nuevas palabras reservadas para el sistema.
	 * @param array $magicWords
	 * @param string $langCode
	 * @return boolean
	 */
	static function GetMagic(&$magicWords, $langCode){
		switch ( $langCode ) {
			default:
			$magicWords[ 'commentsform' ] = array( 0, 'commentsform' );
			$magicWords[ 'commentslist' ] = array( 0, 'commentslist' );
		}
		return true;
	}
}