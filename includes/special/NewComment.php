<?php
/**
 * 
 * Pagina especial que captura la solicitud para agregar un nuevo comentario a un art�culo.
 * @author miguelerm
 *
 */
class NewComment extends SpecialPage
{
	
	/**
	 * 
	 * Constructor por defecto de la p�gina Newcomment
	 */
	function __construct(){
		parent::__construct('NewComment', 'user');
	}
	
	/**
	 * 
	 * Punto de inicio de la p�gina actual. En este caso procesar� la solicitud de agregar un nuevo comentario.
	 * @param $par par�metros trasladados a la p�gina.
	 */
	public function execute( $par ) {
		global $wgOut, $wgRequest, $wgUser;
		
		$articleId = $wgRequest->getInt('articleId');
		$parentId = $wgRequest->getInt('parentId');
		$text = $wgRequest->getText('text');
		$error = false;
		
		$content = '';
		
		if( $articleId && strlen($text) > 0) {
			
			$article = Article::newFromId($articleId);
			
			if ($article != null){
				
				$comment = new Comment($articleId);
				$comment->setParentCommentId($parentId);
				$comment->setText($text);
				$comment->save();
					
				$title = $article->getTitle();
				
				$content .= wfMsg( 'newcomment-Message', $text, $title );
								
			}else{
				$content .= wfMsg('invalid-article');
				$error = true;
			}
			
		}else{
			$content .= wfMsg('invalid-args');
			$error = true;
		}
		
		if ($error){
			$content .= "\n" . wfMsg( 'newcomment-error');
			$pagetitle = Title::makeTitle( NS_SPECIAL, wfMsg( 'newcomment-errortitle' ) );
		} else {
			$pagetitle = Title::makeTitle( NS_SPECIAL, wfMsg( 'newcomment-title' ) );
		}

		
		
		$wgOut->setTitle( $pagetitle);
		$wgOut->addWikiText($content);
		
	}
	
}