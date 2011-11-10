<?php
/**
 * 
 * Pagina especial que permite que un usuario miembro del grupo "commentadmin" administre
 * (apruebe o elimine) los comentarios existentes.
 * @author miguelerm
 *
 */
class CommentsAdministration extends SpecialPage {
	
	/**
	*
	* Constructor por defecto de la página PendingComments
	*/
	function __construct(){
		parent::__construct('CommentsAdministration', 'commentadmin');
	}
	
	/**
	*
	* Punto de inicio de la página actual. En este caso mostrará la lista de comentarios pendientes de aprobar.
	* @param $par parámetros trasladados a la página.
	*/
	public function execute( $par ) {
		
		global $wgOut, $wgRequest, $wgUser;
		
		if (!$wgUser->isAllowed( 'commentadmin' )){
			$wgOut->addWikiText('==No tiene privilegios para administrar los comentarios==');
			return;
		}
		
		$action = $wgRequest->getText('action');
		$page = $wgRequest->getInt('page');
		
		$page = $page ? $page : 1 ;
		$action = strlen($action) > 0 ? $action : 'pending' ;
		
		switch ($action) {
			
			case 'listall':
			$this->renderCommentsList(Comment::getAll($page));
			break;
			
			case 'delete':
			$this->deleteComment($wgRequest->getInt('commentid'));
			break;
			
			case 'approve':
			$this->approveComment($wgRequest->getInt('commentid'));
			
			break;
			
			default:
			$this->renderCommentsList(Comment::getNotApproved($page));
			break;
		}
		
	}
	
	/**
	 * 
	 * Aprueba un comentario, cambiando su estado de 0 a 1.
	 * @param int $commentId Identificador único del comentario.
	 */
	private function approveComment($commentId){
		
		global $wgOut;
		
		if ($commentId) 
			$comment = Comment::getSingle($commentId);
		
		if ($comment != null) 
			$comment->approve();
		
		$wgOut->redirect( $this->getTitle()->getLocalURL() );
		
	}
	
	/**
	 * 
	 * Elimina un comentario.
	 * @param int $commentId Identificador único del comentario.
	 */
	private function deleteComment($commentId){
	
		global $wgOut;
		
		if ($commentId)
			$comment = Comment::getSingle($commentId);
	
		if ($comment != null)
			$comment->delete();
		
		$wgOut->redirect( $this->getTitle()->getLocalURL() );
			
	}
	
	/**
	 * 
	 * Renderiza la lista de comentarios como una lista HTML.
	 * @param array $comments Arreglo que contiene los comentarios que se desea mostrar.
	 */
	private function renderCommentsList($comments){
		
		global $wgOut;
		
		if (count($comments)) {
				
			$wgOut->addHTML('<ul>');
			
			$currentTitle = $this->getTitle();
				
			/* @var $comment Comment */
			foreach ($comments as $comment) {
		
				$wgOut->addHTML('<li>');
				$wgOut->addHTML(   '<div class="comentario">');
				$wgOut->addWikiText(   "El usuario '''" . $comment->getUserRealName() . "''' comento en el articulo [[" . $comment->getArticleName() . "]] el dia ''" . date(wfMsg('commentlist-dateformat'), $comment->getDate())  . "'' lo siguiente:\n");
		
				$html = htmlspecialchars( $comment->getText() );
				$html = str_replace( array("\r\n", "\n", "\r"), "<br />", $html);
		
				$wgOut->addHTML( $html );
		
				//Si el comentario es una respuesta de otro comentario,
				//se debe genera un link hacia el comentario original.
				if ($comment->getParentCommentId() > 0)
					$wgOut->addHTML(       '<p><a href="#" title="En respuesta de...">En respuesta de...</a></p>');
		
				//Se obtienen las urls para aprobar y para eliminar el comentario.
				$approveUrl = $currentTitle->getLocalURL('action=approve&commentid=' . $comment->getId());
				$deleteUrl = $currentTitle->getLocalURL('action=delete&commentid=' . $comment->getId());
		
				$wgOut->addHTML(   '</div>');
				$wgOut->addHTML(   '<div class="operaciones">');
				$wgOut->addHTML(       '<a href="' . $approveUrl . '" onClick="return confirm(\'&iquest;Seguro que desea aprobar el comentario del usuario ' . $comment->getUserRealName() . '?\')" title="Aprobar">Aprobar</a> ');
				$wgOut->addHTML(       '<a href="' . $deleteUrl . '" onClick="return confirm(\'&iquest;Seguro que desea eliminar el comentario del usuario ' . $comment->getUserRealName() . '?\')" title="Eliminar">Eliminar</a>');
				$wgOut->addHTML(   '</div>');
				$wgOut->addHTML('</li>');
		
			};
				
			$wgOut->addHTML('</ul>');
		
				
		} else {
			$wgOut->addWikiText('No hay ningun mensaje para mostrar...');
		}
		
		$pagetitle = Title::makeTitle( NS_SPECIAL, wfMsg( 'comments-title' ) );
		$wgOut->setTitle( $pagetitle);
		
	}
	
}