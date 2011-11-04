<?php

$dir = dirname( __FILE__ ) . '/';

/* Cargando los mensajes multi-idioma */
$wgExtensionMessagesFiles['WikiComments'] = $dir . 'WikiComments.i18n.php';

/* Cargando las clases que se utilizaran por la extensión */
$wgAutoloadClasses['Comment'                ] = $dir . 'includes/Comment.php';
$wgAutoloadClasses['CommentsFunctions'      ] = $dir . 'includes/CommentsFunctions.php';
$wgAutoloadClasses['CommentsParser'         ] = $dir . 'includes/CommentsParser.php';
$wgAutoloadClasses['CommentsLanguage'       ] = $dir . 'includes/CommentsLanguage.php';
$wgAutoloadClasses['CommentsDB'             ] = $dir . 'includes/CommentsDB.php';
$wgAutoloadClasses['NewComment'             ] = $dir . 'includes/special/NewComment.php'; 
$wgAutoloadClasses['CommentsAdministration' ] = $dir . 'includes/special/CommentsAdministration.php';

/* Registrando los nuevos Hooks al sistema */
$wgHooks['ParserFirstCallInit'][] = 'CommentsParser::FirstCallInit';
$wgHooks['LanguageGetMagic'   ][] = 'CommentsLanguage::GetMagic';

/* Registrando las nuevas paginas especiales */
$wgSpecialPages['NewComment'              ] = 'NewComment';
$wgSpecialPages['CommentsAdministration'  ] = 'CommentsAdministration';

$wgSpecialPageGroups['NewComment'             ] = 'comments';
$wgSpecialPageGroups['CommentsAdministration' ] = 'comments';

/* Registrando los nuevos permisos al sistema */
$wgAvailableRights[] = 'commentadmin';
$wgGroupPermissions['commentadmin']['commentadmin'] = true;
