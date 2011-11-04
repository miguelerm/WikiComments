<?php
/**
 * 
 * Clase Comment, representa tanto la entidad 'Comentario', así como la lógica para administrarlos.
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
	private $status;
	private $articleName;
	
	/* Propiedades públicas */
	
	/**
	 * 
	 * Obtiene el identificador único del comentario.
	 * @return int
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
	 * @return text
	 */
	public function getText(){ 
		return $this->text; 
	}
	
	/**
	 * 
	 * Obtiene la fecha en que se creó el comentario.
	 * @return int 
	 */
	public function getDate(){
		return $this->date;
	}
	
	/**
	 * 
	 * Obtiene la dirección IP desde la que se creó el comentario.
	 * @return text
	 */
	public function getIpAddress()
	{
		return $this->ipAddress;
	}
	
	public function getArticleName(){
		return $this->articleName;
	}
	
	
	/**
	 * 
	 * Establece el comentario que originó el comentario actual (respuesta de)
	 * @param int $parentCommentId Identificador único del comentario al cual responde el comentario actual.
	 */
	public function setParentCommentId($parentCommentId){
		$this->parentCommentId = $parentCommentId;
	}
	
	/**
	 * 
	 * Obtiene el comentario del cual es respuesta el comentario actual.
	 * @return int
	 */
	public function getParentCommentId(){
		return $this->parentCommentId;
	}
	
	/**
	 * 
	 * Obtiene el nombre del usuario que creó el comentario.
	 * @return text
	 */
	public function getUserRealName(){
		if( strlen($this->userRealName) == 0)
			return $this->userName;
		else
			return $this->userRealName;
	}
	
	/**
	 * 
	 * Obtiene los comentarios que estan marcados como respuesta del comentario actual.
	 * @return array
	 */
	public function getChildComments(){
		return $this->childComments;
	}
	
	
	/* Constructores */
	
	/**
	 * 
	 * Constructor por defecto del comentario.
	 * @param int $pageId Identidicador único del artículo en la que se mostrará el comentario.
	 * @param int $userId Identificador único del usuario que creó el comentario.
	 */
	function __construct($articleId){
		$this->initialize($articleId);
	}
	
	/* Métodos privados */
		
	/**
	 * 
	 * Inicializa un comentario con los valores indicados.
	 * @param int $commentId Identificador único del comentario (0 si es un comentario nuevo)
	 * @param int $articleId Identificador único del artículo en el que se muestra el comentario.
	 * @param int $userId Identificador único del usuario que creó el comentario.
	 */
	private function initialize($articleId)
	{
		global $wgUser;
		
		$this->id = 0;
		$this->date = time();
		$this->articleId = $articleId;
		$this->userId = $wgUser->getId();
		$this->userName = $wgUser->getName();
		$this->userRealName = $wgUser->getRealName();
		$this->ipAddress = $_SERVER['REMOTE_ADDR'];
	}
	
	static private function getCommentsFromDB($conds, $options){
		
		global $wgDBprefix;
		global $wgShowSQLErrors;
		
		
		$tables = array( "${wgDBprefix}WikiComments", "${wgDBprefix}user", "${wgDBprefix}page"  );
		$fields = array( 'id', 'article_id', "`${wgDBprefix}user`.`user_id`", 'text', 'UNIX_TIMESTAMP(creation_date) AS creation_date', 'parent_id', 'user_ip', 'user_name', 'user_real_name', 'status', "`${wgDBprefix}page`.`page_title`" );
		$join_conds = array('user' => array('INNER JOIN', "`${wgDBprefix}user`.`user_id` = `${wgDBprefix}WikiComments`.`user_id`"),
		                    'page' => array('INNER JOIN', "`${wgDBprefix}page`.`page_id` = `${wgDBprefix}WikiComments`.`article_id`"));
		
		$database = wfGetDB( DB_SLAVE );
		
		$wgShowSQLErrors = true;
		
		$result = $database->select( $tables, $fields, $conds, __METHOD__, $options, $join_conds );
		
		$comments = array();
		
		foreach ($result as $row)
		{
			$commentId = $row->id;
			$comment = new Comment($row->article_id, $row->user_id);
			$comment->id = $commentId;
			$comment->text = $row->text;
			$comment->date = $row->creation_date;
			$comment->parentCommentId = $row->parent_id;
			$comment->userName = $row->user_name;
			$comment->userRealName = $row->user_real_name;
			$comment->ipAddress = $row->user_ip;
			$comment->articleName = $row->page_title;
			$comments[$commentId] = $comment;
		}
		
		return $comments;
		
	}
	
	/* Métodos públicos */
	
	/**
	 * 
	 * Agrega un nuevo comentario como respuesta del comentario instanciado.
	 * @param Comment $comment Comentario que se quiere agregar como respuesta.
	 */
	public function addChildComment(Comment $comment){
		$this->childComments[] = $comment;
	}
	
	/**
	 * 
	 * Indica si un comentario tiene respuestas o no.
	 * @return boolean Retorna true si el comentario tiene respuestas o false en caso contrario.
	 */
	public function hasChildComments(){
		return count($this->childComments) > 0;
	}
	
	/**
	 * 
	 * Persiste la información del comentario en la base de datos.
	 * @return NULL
	 */
	public function save(){
		
		global $wgDBprefix;
		
		$database = wfGetDB( DB_MASTER );
		
		$database->insert(
					"${wgDBprefix}WikiComments",
					array(
						'article_id' => $this->articleId,
						'user_id' => $this->userId,
						'text' => $this->text,
						'creation_date' => date( 'Y-m-d H:i:s' , $this->date),
						'parent_id' => $this->parentCommentId,
						'user_ip' => $this->ipAddress,
						'status' => 0
						),
						__METHOD__
					);
		
		$commentId = $database->insertId();
		$database->commit();
		
		$this->id = $commentId;
		
	}
	
	/**
	 * 
	 * Obtiene los comentarios que han sido aprobados para un artículo en particular.
	 * @param int $articleId
	 */
	static public function getApproved($articleId){
		
		
		$conds = array('article_id' => $articleId, 'status' => 1);
		$options['ORDER BY'] = 'parent_id ASC, creation_date ASC';
		
		$comments = self::getCommentsFromDB($conds, $options);
		
		$commentsToReturn = array();
					
		foreach ($comments as $currentComment)
			if ($currentComment->parentCommentId != 0 && $comments[$currentComment->parentCommentId] != null)
				$comments[$currentComment->parentCommentId]->addChildComment($currentComment);
			else
				$commentsToReturn[] = $currentComment;
		
		return $commentsToReturn;
		
	}
	
	static public function getNotApproved($page){
		
		$conds = array('status' => 0);
		$options['ORDER BY'] = 'article_id ASC, creation_date ASC';
		
		$comments = self::getCommentsFromDB($conds, $options);
		
		return $comments;
		
	}
	
	static public function getAll($page){
		
		$conds = array();
		$options['ORDER BY'] = 'creation_date ASC, article_id ASC';
		
		$comments = self::getCommentsFromDB($conds, $options);
		
		return $comments;
		
	}

}