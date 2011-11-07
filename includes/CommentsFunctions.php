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
		
		global $wgUser, $wgTitle;
		
		// Desabilitando el cache, de lo contrario los
		// comentarios se actualizarian solamente cuando
		// un usuario modifique un artículo.
		$parser->disableCache();
	
		if (!$wgUser->isLoggedIn()) return array('<div class="formularioComentarios"><h2>' . wfMsg('commentform-message') . '</h2><span class="error">debe estar autenticado para colocar un mensaje</span></div>', 'noparse' => true, 'isHTML' => true );;
		
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
		$content .=             '<li style="display:none" id="reply">';
		$content .=                '<label for="replytext">En respuesta de:</label>';
		$content .=                '<textarea rows="5" cols="20" name="replytext" id="replytext" disabled="disabled"></textarea>';;
		$content .=                '<a href="#" title="Cancelar" onclick="return cancelarRespuesta()">Cancelar</a>';;
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
		
		$content .= '<script type="text/javascript">';
		$content .= 'function cancelarRespuesta(){';
		$content .=    '$(\'.formularioComentarios\').children().find(\'[name=parentId]\').val(0);';
		$content .=    '$(\'#reply\').hide();';
		$content .=    '$(\'#replytext\').val("");';
		$content .=    '$(\'#text\').focus();';
		$content .=    'return false;';
		$content .= '}';
		$content .= '</script>';
		
		return array( $content, 'noparse' => true, 'isHTML' => true );
		
	}

	/**
	 * 
	 * Renderiza el listado de comentarios que se han colocado en un artículo.
	 * @param Parser $parser
	 */
	public function renderList(&$parser){
	
		global $wgTitle, $wgScriptPath, $wgOut;
		
		$wgOut->addScript("<script type=\"text/javascript\" src=\"" . $wgScriptPath . "/extensions/jQuery/jquery-1.7.min.js\"></script>\n");
		
		$comments = Comment::getApproved($wgTitle->getArticleID());
		
		$content  = '';
		$content .= '<link rel="stylesheet" type="text/css" href="'.$wgScriptPath.'/extensions/WikiComments/main.css" media="screen" />';
		$content .= '<div class="listaComentarios">';
		
		if(count($comments) > 0){
			$content .= '<h2>' . wfMsg('commentlist-message') . '</h2>';
			$content .= '<ul>';
			foreach ($comments as $comment)
			{
				$content .= $this->getCommentHtml($comment, $parser);
			}
			$content .= '</ul>';
		}
		else{
			$content .= '<span>' . wfMsg('commentlist-empty') . '</span>';
		}
		
		$content .= '</div>';
		
		
		$content .= '<script type="text/javascript">';
		$content .= 'function responder(id){';
		$content .=    '$(\'.formularioComentarios\').children().find(\'[name=parentId]\').val(id);';
		$content .=    '$(\'#reply\').show();';
		$content .=    '$(\'#replytext\').val($(\'#comment\' + id + \'text\').text());';
		$content .=    '$(\'#text\').focus();';
		$content .=    'return false;';
		$content .= '}';
		$content .= '</script>';
		
		return array( $content, 'noparse' => true, 'isHTML' => true );
		
	}
	
	/**
	 * 
	 * Obtiene el html necesario para mostrar un comentario y sus respuestas.
	 * @param Comment $comment
	 */
	private function getCommentHtml(Comment $comment, $parser){
		
		global $wgUser;
		
		$content = '';
		$content .= '<li>';
		$content .=    '<div class="comentario">';
		$content .=       '<strong>' . $comment->getUserRealName() . '</strong> <em>' . date(wfMsg('commentlist-dateformat'), $comment->getDate()) . '</em>: <span id="comment' . $comment->getId() . 'text">' . str_replace( array("\r\n", "\n", "\r"), "<br />", htmlspecialchars ( $comment->getText() )) . '</span>';
		$content .=    '</div>';
		
		if ($wgUser->isLoggedIn()){
			$content .=    '<div class="acciones">';
			$content .=       '<a href="#" onclick="return responder(' . $comment->getId() . ')" title="Responder">Responder</a>';
			$content .=    '</div>';
		}
		
		if ($comment->hasChildComments()) {
			$content .= '<div class="subcomentarios">';
			$content .=    '<ul>';
			
			foreach ($comment->getChildComments() as $childComment)
				$content .= $this->getCommentHtml($childComment, $parser);
			
			$content .=    '</ul>';
			$content .= '</div>';
		}
		
		$content .= '</li>';
		
		return $content;
	}
	

}