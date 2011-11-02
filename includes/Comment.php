<?php
/**
 * 
 * Clase Comment, representa tanto la entidad 'Comentario', as� como la l�gica para administrarlos.
 * @author miguelerm
 *
 */
class Comment{
	
	/* campos privados */
	
	private $id;
	private $articleId;
	private $userId;
	private $userName;
	private $userRealName;
	private $text;
	private $date;
	private $parentCommentId;
	private $ipAddress;
	private $childComments;
	
	/* Propiedades p�blicas */
	
	/**
	 * 
	 * Obtiene el identificador �nico del comentario.
	 */
	public function getId(){
		return $this->id;	
	}	
	
	/**
	 * 
	 * Establece el valor del texto que conforma el cuerpo del comentario.
	 * @param String $newText Nuevo texto que se desea asignar al comentario.
	 */
	public function setText($newText){ 
		$this->text = $newText; 
	}
	
	/**
	 * 
	 * Obtiene el valor del texto que conforma el cuerpo del comentario.
	 */
	public function getText(){ 
		return $this->text; 
	}
	
	/**
	 * 
	 * Obtiene la fecha en que se cre� el comentario. 
	 */
	public function getDate(){
		return $this->date;
	}
	
	/**
	 * 
	 * Establece la direcci�n IP desde la que se cre� el comentario.
	 * @param String $newIp Direcci�n IP que se quiere asignar al comentario.
	 */
	public function setIpAddress($newIp)
	{
		$this->ipAddress = $newIp;
	}
	
	/**
	 * 
	 * Obtiene la direcci�n IP desde la que se cre� el comentario.
	 */
	public function getIpAddress()
	{
		return $this->ipAddress;
	}
	
	/**
	 * 
	 * Establece el comentario que origin� el comentario actual (respuesta de)
	 * @param int $parentCommentId Identificador �nico del comentario al cual responde el comentario actual.
	 */
	public function setParentCommentId($parentCommentId){
		$this->parentCommentId = $parentCommentId;
	}
	
	/**
	 * 
	 * Obtiene el comentario del cual es respuesta el comentario actual.
	 */
	public function getParentCommentId(){
		return $this->parentCommentId;
	}
	
	/**
	 * 
	 * Obtiene los comentarios que estan marcados como respuesta del comentario actual.
	 */
	public function getChildComments(){
		return $this->childComments;
	}
	
	
	/* Constructores */
	
	/**
	 * 
	 * Constructor por defecto del comentario.
	 * @param int $pageId Identidicador �nico del art�culo en la que se mostrar� el comentario.
	 * @param int $userId Identificador �nico del usuario que cre� el comentario.
	 */
	function __construct($articleId){
		$this->initialize($articleId);
	}
	
	/* M�todos privados */
		
	/**
	 * 
	 * Inicializa un comentario con los valores indicados.
	 * @param int $commentId Identificador �nico del comentario (0 si es un comentario nuevo)
	 * @param int $articleId Identificador �nico del art�culo en el que se muestra el comentario.
	 * @param int $userId Identificador �nico del usuario que cre� el comentario.
	 */
	private function initialize($articleId)
	{
		$this->id = 0;
		$this->date = getdate();
		$this->articleId = $articleId;
		//$this->userId = $userId;
		//$this->userName = $userName;
		//$this->userRealName = $userRealName;
	}
	
	static private function getSingleComment($id){
		return null;
	}
	
	/* M�todos p�blicos */
	
	public function addChildComment(Comment $comment){
		$this->childComments[] = $comment;
	}
	
	public function hasChildComments(){
		return count($this->childComments) > 0;
	}
	
	static public function getApproved($articleId){
		
		global $wgDBprefix;
		
		$tables = array( $wgDBprefix . "WikiComments", $wgDBprefix . "User" );
		$fields = array( 'id', 'article_id', '`User`.`user_id`', 'text', 'UNIX_TIMESTAMP(creation_date) AS timestamp', 'parent_id', 'user_ip', 'user_name', 'user_real_name' );
		$conds = array('article_id' => $articleId);
		$join_conds = array('WikiComments' => array('INNER JOIN', '`User`.`user_id` = `WikiComments`.`user_id`'));
		$options['ORDER BY'] = 'parent_id ASC, creation_date DESC';
		
		$database =& wfGetDB( DB_SLAVE );
		
		$result = $dbr->select( $tables, $fields, $conds, __METHOD__, $options, $joinConds );
		
		$comments = array();
		$commentsToReturn = array();
		
		foreach ($result as $row)
		{
			$commentId = $row->id;
			$comment = new Comment($articleId, $userId);
			$comment->id = $commentId;
			$comment->text = $row->text;
			$comment->date = $row->creation_date;
			$comment->parentCommentId = $row->parent_id;
			$comment->userName = $row->user_name;
			$comment->userRealName = $row->user_real_name;
			$comment->ipAddress = $row->user_ip;
			$comments[$commentId] = $comment;			
		}
		
		foreach ($comments as $currentComment)
			if ($currentComment->parentCommentId != 0 && $comments[$currentComment->parentCommentId] != null)
				$comments[$currentComment->parentCommentId]->addChildComment($currentComment);
			else
				$commentsToReturn[] = $currentComment;
		
		return $commentsToReturn;
		
	}
	
	static public function getNotApproved(){
		return null;
	}
	
	static public function renderList(&$parser){
	
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
	
	static private function getCommentHtml(Comment $comment){
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