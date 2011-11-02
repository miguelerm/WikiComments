<?php
/**
 * 
 * Clase CommentsFunctions: contiene funciones de apoyo para el renderizado de la UI de la extensión WikiComments
 * @author miguelerm
 *
 */
class CommentsFunctions{

	/**
	 * 
	 * Renderiza el formulario html para agregar un nuevo comentario
	 * @param Parser $parser
	 * @return string|multitype:boolean string
	 */
	function renderForm(&$parser){
		
		global $wgUser;
		global $wgTitle;
		
		// Desabilitando el cache, de lo contrario los
		// comentarios se actualizarian solamente cuando
		// un usuario modifique un artículo.
		$parser->disableCache();
	
		if (!$wgUser->isLoggedIn()) return '<div class="formularioComentarios" style="color:#ff0000"><p>debe estar autenticado para colocar un mensaje</p></div>';
		
		$actionUrl = SpecialPage::getTitleFor( 'NewComment' )->getLocalURL();
		
		$username = $wgUser->getRealName();
		
		if(strlen($username) == 0)
			$username = $wgUser->getName();
		
		$content  = '';
		$content .= '<div class="formularioComentarios">';
		$content .=    '<h2>' . wfMsg('commentform-message') . '</h2>';
		$content .=    '<form action="' . $actionUrl . '" method="POST">';
		$content .=       '<fieldset>';
		$content .=          '<ul>';
		$content .=             '<li>';
		$content .=                '<label for="usr_nombre">' . wfMsg('username-label') . ':</label>';
		$content .=                '<input type="text" name="usr_nombre" id="usr_nombre" value="' . mysql_real_escape_string($username) . '" disabled="disabled" />';
		$content .=             '</li>';
		$content .=             '<li>';
		$content .=                '<input type="hidden" name="articleId" value="' . $wgTitle->getArticleID() . '" />';
		$content .=                '<input type="hidden" name="parentId" value="0" />';
		$content .=                '<label for="text">' . wfMsg('message-label') . ':</label>';
		$content .=                '<textarea rows="5" cols="20" name="text" id="text"></textarea>';
		$content .=             '</li>';
		$content .=          '</ul>';
		$content .=       '</fieldset>';
		$content .=       '<input type="submit" value="' . wfMsg('addcomment-button') . '" />';
		$content .=    '</form>';
		$content .= '</div>';
		
		return array( $content, 'noparse' => true, 'isHTML' => true );
		
	}

	/**
	 * 
	 * Renderiza el listado de comentarios que se han colocado en un artículo.
	 * @param Parser $parser
	 */
	public function renderList(&$parser){
	
		global $wgTitle;
		
		$comments = Comment::getApproved($wgTitle->getArticleID());
		
		$content  = '';
		$content .= '<div class="listaComentarios">';
		
		if(count($comments) > 0){
			$content .= '<h2>' . wfMsg('commentlist-message') . '</h2>';
			$content .= '<ul>';
			foreach ($comments as $comment)
			{
				$content .= $this->getCommentHtml($comment);
			}
			$content .= '</ul>';
		}
		else{
			$content .= '<span>' . wfMsg('commentlist-empty') . '</span>';
		}
		
		return $content;
		
	}
	
	/**
	 * 
	 * Obtiene el html necesario para mostrar un comentario y sus respuestas.
	 * @param Comment $comment
	 */
	private function getCommentHtml(Comment $comment){
		$content = '';
		$content .= '<li>';
		$content .= '<strong>' . $comment->getUserRealName() . '</strong> <em>' . date(wfMsg('commentlist-dateformat'), $comment->getDate()) . '</em>: <span>' . $comment->getText() . '</span>';
		
		if ($comment->hasChildComments()) {
			$content .= '<ul>';
			
			foreach ($comment->getChildComments() as $childComment)
				$content .= Comment::getCommentHtml($childComment);
			
			$content .= '</ul>';
		}
		
		$content .= '</li>';
		return $content;
	}
	

}