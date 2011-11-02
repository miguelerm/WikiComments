<?php

$dir = dirname( __FILE__ ) . '/';

$wgExtensionMessagesFiles['WikiComments'] = $dir . 'WikiComments.i18n.php';

$wgAutoloadClasses['Comment'          ] = $dir . 'includes/Comment.php';
$wgAutoloadClasses['CommentsFunctions'] = $dir . 'includes/CommentsFunctions.php';
$wgAutoloadClasses['CommentsParser'   ] = $dir . 'includes/CommentsParser.php';
$wgAutoloadClasses['CommentsLanguage' ] = $dir . 'includes/CommentsLanguage.php';
$wgAutoloadClasses['CommentsDB'       ] = $dir . 'includes/CommentsDB.php';

$wgHooks['ParserFirstCallInit'][] = 'CommentsParser::FirstCallInit';
$wgHooks['LanguageGetMagic'][]    = 'CommentsLanguage::GetMagic';