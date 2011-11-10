<?php
/**
 *
 * Clase que encapsula toda la lógica agregada a los eventos de renderizado de páginas de MediaWiki
 * @author miguel.erm
 *
 */
class CommentsHooks{


	public static function OutputPageBeforeHTML( &$op, &$text ){

		global $wgUser, $wgTitle;

		if (!$wgUser->isLoggedIn()){
			$text .= '<div class="formularioComentarios"><h2>' . wfMsg('commentform-message') . '</h2><span class="error">debe estar autenticado para colocar un mensaje</span></div>';
			return true;
		}

		$text .= self::renderForm();
		$text .= self::renderList();

		return true;

	}


	/**
	 *
	 * Renderiza el formulario html para agregar un nuevo comentario
	 * @return string html del formulario
	 */
	private static function renderForm(){

		global $wgUser, $wgTitle;

		if (!$wgUser->isLoggedIn()) return array('<div class="formularioComentarios"><h2>' . wfMsg('commentform-message') . '</h2><span class="error">debe estar autenticado para colocar un mensaje</span></div>', 'noparse' => true, 'isHTML' => true );;

		$sp = new NewComment();
		$actionUrl = $sp->getTitle()->getLocalURL();
					
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
			
		return $content;

	}

	/**
	 *
	 * Renderiza el listado de comentarios que se han colocado en un artículo.
	 * @return string html necesario para renderizar la lista de comentarios.
	 */
	private static function renderList(){

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
				$content .= self::getCommentHtml($comment);
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

		return $content;

	}

	/**
		*
		* Obtiene el html necesario para mostrar un comentario y sus respuestas.
		* @param Comment $comment
		*/
	private static function getCommentHtml(Comment $comment){

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
			$content .= self::getCommentHtml($childComment, $parser);

			$content .=    '</ul>';
			$content .= '</div>';
		}

		$content .= '</li>';

		return $content;
	}


}