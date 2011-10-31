<?php
class Comment{
	
	private $id;
	private $pageId;
	private $userId;
	private $text;
	private $date;
	private $parentCommentId;
	private $ipAddress;
	private $childComments;
	
	public function setText($newText){ 
		$this->text = $newText; 
	}
	
	public function getText(){ 
		return $this->text; 
	}
	
	public function setDate($newDate){
		$this->date = $newDate;
	}
	
	public function getDate(){
		return $this->date;
	}
	
	public function setIpAddress($newIp)
	{
		$this->ipAddress = $newIp;
	}
	
	public function getIpAddress()
	{
		return $this->ipAddress;
	}
	
	public function setParentComment(Comment $parentComment){
		$this->parentCommentId = $parentComment->id;
	}
	
	public function getParrentComment(){
		return self::getSingleComment($this->parentCommentId);
	}
	
	public function getChildComments(){
		return $this->childComments;
	}
	
	function __construct($id)
	{
		$this->id = $id;
	}
	
	function __construct($pageId, $userId){
		$this->id = 0;
		$this->pageId = $pageId;
		$this->userId = $userId;
	}
	
	static private function getSingleComment($id){
		return null;
	}
	
	static public function getApproved($pageId){
		return null;
	}
	
	static public function getNotApproved(){
		return null;
	}
}