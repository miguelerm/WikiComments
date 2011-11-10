<?php

$dir = dirname( __FILE__ ) . '/';

/* Cargando los mensajes multi-idioma */
$wgExtensionMessagesFiles['WikiComments'] = $dir . 'WikiComments.i18n.php';

/* Cargando las clases que se utilizaran por la extensi�n */
$wgAutoloadClasses['Comment'                ] = $dir . 'includes/Comment.php';
$wgAutoloadClasses['CommentsHooks'          ] = $dir . 'includes/CommentsHooks.php';
$wgAutoloadClasses['CommentsFunctions'      ] = $dir . 'includes/CommentsFunctions.php';
$wgAutoloadClasses['CommentsParser'         ] = $dir . 'includes/CommentsParser.php';
$wgAutoloadClasses['CommentsLanguage'       ] = $dir . 'includes/CommentsLanguage.php';
$wgAutoloadClasses['CommentsDB'             ] = $dir . 'includes/CommentsDB.php';
$wgAutoloadClasses['NewComment'             ] = $dir . 'includes/special/NewComment.php'; 
$wgAutoloadClasses['CommentsAdministration' ] = $dir . 'includes/special/CommentsAdministration.php';

/* Registrando los nuevos Hooks al sistema */
$wgHooks['OutputPageBeforeHTML'][] = 'CommentsHooks::OutputPageBeforeHTML';

/* Registrando las nuevas paginas especiales */
$wgSpecialPages['NewComment'              ] = 'NewComment';
$wgSpecialPages['CommentsAdministration'  ] = 'CommentsAdministration';

$wgSpecialPageGroups['NewComment'             ] = 'comments';
$wgSpecialPageGroups['CommentsAdministration' ] = 'comments';

/* Registrando los nuevos permisos al sistema */
$wgAvailableRights[] = 'commentadmin';
$wgGroupPermissions['commentadmin']['commentadmin'] = true;
