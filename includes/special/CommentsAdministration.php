<?php
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
	
		
		$currentTitle = $this->getTitle();
		$action = $wgRequest->getText('action');
		$page = $wgRequest->getInt('page');
		$comments = array();
		
		$page = $page ? $page : 1 ;
		$action = strlen($action) > 0 ? $action : 'pending' ;
		
		switch ($action) {
			
			case 'listall':
			$comments = Comment::getAll($page);
			break;
			
			case 'delete':{
				$commentId = $wgRequest->getInt('commentid');
				
				if ($commentId){
					
					$wgOut->addWikiText('Comentario ' .  $commentId);
					
					$comment = Comment::getSingle($commentId);
					
					if ($comment != null){
						$wgOut->addWikiText('Comentario no nulo: ' .  $comment->getText());
						$comment->delete();
					}
						
				}
				
				$wgOut->redirect( $currentTitle->getLocalURL() );
			}
			break;
			
			case 'approve':{
				
				$commentId = $wgRequest->getInt('commentid');

				if ($commentId){

					$wgOut->addWikiText('Comentario ' .  $commentId);
						
					$comment = Comment::getSingle($commentId);
					
					if ($comment !=null){
						$wgOut->addWikiText('Comentario no nulo: ' .  $comment->getText());
						$comment->approve();
					}
				}
				
				$wgOut->redirect( $currentTitle->getLocalURL() );
			}
			break;
			
			default:
			$comments = Comment::getNotApproved($page);
			break;
		}
		
		if (count($comments)) {
			
			$wgOut->addHTML('<ul>');
			
			/* @var $comment Comment */
			foreach ($comments as $comment) {
				
				$wgOut->addHTML('<li>');
				$wgOut->addHTML(   '<div class="comentario">');
				$wgOut->addWikiText(   "El usuario '''" . $comment->getUserRealName() . "''' comento en el articulo [[" . $comment->getArticleName() . "]] el dia ''" . date(wfMsg('commentlist-dateformat'), $comment->getDate())  . "'' lo siguiente:\n");
				
				$html = htmlspecialchars( $comment->getText() );
				$html = str_replace( array("\r\n", "\n", "\r"), "<br />", $html);
				
				$wgOut->addHTML( $html );
				
				if ($comment->getParentCommentId() > 0)
					$wgOut->addHTML(       '<p><a href="#" title="En respuesta de...">En respuesta de...</a></p>');

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