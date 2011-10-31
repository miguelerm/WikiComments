<?php
class CommentsDB{
	
	static private $dbchecked = false;
	
	static function CheckDatabase(){
		
		if(self::$dbchecked) return null;
		
		global $wgDBprefix;
		
		$dbr =& wfGetDB( DB_SLAVE );
		
		// Check if 'WikiComments' database tables exists
		if (!$dbr->tableExists('WikiComments'))
		{
			$sql  = "CREATE TABLE `".$wgDBprefix."WikiComments` (";
			$sql .=    "`id` int(11) NOT NULL auto_increment,";
			$sql .=    "`page_id` int(11) NOT NULL default '0',";
			$sql .=    "`user_id` int(11) NOT NULL default '0',";
			$sql .=    "`text` text NOT NULL,";
			$sql .=    "`creation_date` datetime NOT NULL default '0000-00-00 00:00:00',";
			$sql .=    "`parent_id` int(11) NOT NULL default '0',";
			$sql .=    "`user_ip` varchar(45) NOT NULL default '',";
			$sql .=    "PRIMARY KEY  (`id`),";
			$sql .=    "KEY `page_id_index` (`page_id`),";
			$sql .=    "KEY `user_id_index` (`user_id`)";
			$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			$res = $dbr->query( $sql, __METHOD__ );
		}
		
		self::$dbchecked = true;
		
	}
	
}