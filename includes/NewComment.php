<?php
class NewComment extends SpecialPage
{
	
	function __construct(){
		parent::__construct("NewComment");
	}
	
	/**
	* Punto de inicio de la página actual. En este caso procesará la solicitud de agregar un nuevo comentario.
	*
	* @param $par parámetros trasladados a la página.
	*/
	public function execute( $par ) {
		global $wgOut, $wgRequest, $wgUser;
		
		$articleId = $wgRequest->getInt('articleId');
		$parentId = $wgRequest->getInt('parentId');
		$text = $wgRequest->getText('text');
		$error = false;
		
		$content = '';
		
		if( $articleId ) {
			
			$article = Article::newFromId($articleId);
			
			if ($article != null){
				
				$comment = new Comment($articleId);
				$comment->setParentCommentId($parentId);
				$comment->setText($text);
				$comment->save();
					
				$title = $article->getTitle();
				
				$content .= wfMsg( 'newcomment-Message', $text, $title );
				
			}else{
				$error = true;
			}
			
		}else{
			$error = true;
		}
		
		if ($error){
			$content .= wfMsg( 'newcomment-error');
		}

		$pagetitle = Title::makeTitle( NS_SPECIAL, wfMsg( 'newcomment-title' ) );
		
		$wgOut->setTitle( $pagetitle);
		$wgOut->addWikiText($content);
		
	}
}