<?php
class CommentsFunctions{

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
		$content .=    '<form action="' . $actionUrl . '" method="POST">';
		$content .=       '<fieldset>';
		$content .=          '<legend>Comparta comentario</legend>';
		$content .=          '<ul>';
		$content .=             '<li>';
		$content .=                '<label for="usr_nombre">Nombre:</label>';
		$content .=                '<input type="text" name="usr_nombre" id="usr_nombre" value="' . mysql_real_escape_string($username) . '" disabled="disabled" />';
		$content .=             '</li>';
		$content .=             '<li>';
		$content .=                '<input type="hidden" name="articleId" value="' . $wgTitle->getArticleID() . '" />';
		$content .=                '<input type="hidden" name="parentId" value="0" />';
		$content .=                '<label for="text">Comentario:</label>';
		$content .=                '<textarea rows="5" cols="20" name="text" id="text"></textarea>';
		$content .=             '</li>';
		$content .=          '</ul>';
		$content .=       '</fieldset>';
		$content .=       '<input type="submit" value="Agregar comentario" />';
		$content .=    '</form>';
		$content .= '</div>';
		
		return array( $content, 'noparse' => true, 'isHTML' => true );
		
	}

	public function renderList(&$parser){
	
		global $wgTitle;
		
		$comments = Comment::getApproved($wgTitle->getArticleID());
		
		$content  = '';
		$content .= '<div class="listaComentarios">';
		
		if(count($comments) > 0){
			
			$content .= '<ul>';
			foreach ($comments as $comment)
			{
				$content .= Comment::getCommentHtml($comment);
			}
			$content .= '</ul>';
		}
		else{
			$content .= '<span>No existe ning&uacute;n comentario para este art&iacute;culo.</span>';
		}
		
		return $content;
		
	}
	
	private function getCommentHtml(Comment $comment){
		$content = '';
		$content .= '<li>';
		$content .= '<strong>' . $comment->getUserRealName() . '</strong> <em>' . date(DATE_RFC822, $comment->getDate()) . '</em>: <span>' . $comment->getText() . '</span>';
		
		if ($comment->hasChildComments()) {
			$content .= '<ul>';
			
			foreach ($comment->getChildComments() as $childComment)
				$content .= Comment::getCommentHtml($childComment);
			
			$content .= '</ul>';
		}
		
		$content .= '</li>';
	}
	

}