<?php
/**
 * 
 * Clase CommentsDB: contiene rutinas de apoyo para el acceso a los datos de la extensi�n WikiComments.
 * @author miguelerm
 *
 */
class CommentsDB{
	
	static private $dbchecked = false;
	
	/**
	 * 
	 * Verifica que existan las tablas requeridas en la base de datos, de lo contrario las crea.
	 * @return NULL
	 */
	static function CheckDatabase(){
		
		if(self::$dbchecked) return null;
		
		global $wgDBprefix;
		
		$dbr = wfGetDB( DB_SLAVE );
		
		// Check if 'WikiComments' database tables exists
		if (!$dbr->tableExists('WikiComments'))
		{
			$sql  = "CREATE TABLE `".$wgDBprefix."WikiComments` (";
			$sql .=    "`id` int(11) NOT NULL auto_increment,";
			$sql .=    "`article_id` int(11) NOT NULL default '0',";
			$sql .=    "`user_id` int(11) NOT NULL default '0',";
			$sql .=    "`text` text NOT NULL,";
			$sql .=    "`creation_date` datetime NOT NULL default '0000-00-00 00:00:00',";
			$sql .=    "`parent_id` int(11) NOT NULL default '0',";
			$sql .=    "`user_ip` varchar(45) NOT NULL default '',";
			$sql .=    "`status` TINYINT(1) NOT NULL default 0,";
			$sql .=    "PRIMARY KEY  (`id`),";
			$sql .=    "KEY `wikicomment_article_id_index` (`article_id`),";
			$sql .=    "KEY `wikicomment_user_id_index` (`user_id`)";
			$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			$res = $dbr->query( $sql, __METHOD__ );
		}
		
		self::$dbchecked = true;
		
	}
	
}