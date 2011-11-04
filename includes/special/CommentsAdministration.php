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
	
		$action = $wgRequest->getText('action');
		$page = $wgRequest->getInt('page');
		$comments = array();
		
		$page = $page ? $page : 1 ;
		$action = strlen($action) > 0 ? $action : 'pending' ;
		
		switch ($action) {
			case 'listall':
			$comments = Comment::getAll($page);
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
				$wgOut->addWikiText(   "El usuario '''" . $comment->getUserRealName() . "''' comento en el articulo [[" . $comment->getArticleName() . "]] el dia ''" . date(wfMsg('commentlist-dateformat'), $comment->getDate())  . "'' lo siguiente:\n" . $comment->getText());
				
				if ($comment->getParentCommentId() > 0)
					$wgOut->addHTML(       '<p><a href="#" title="En respuesta de...">En respuesta de...</a></p>');
								
				$wgOut->addHTML(   '</div>');
				$wgOut->addHTML(   '<div class="operaciones">');
				$wgOut->addHTML(       '<a href="#" title="Aprobar">Aprobar</a> ');
				$wgOut->addHTML(       '<a href="#" title="Eliminar">Eliminar</a>');
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