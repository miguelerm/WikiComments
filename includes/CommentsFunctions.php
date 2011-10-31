<?php
class CommentsFunctions{

	function renderForm(&$parser){
		
		global $wgUser;
		global $wgTitle;
		
		// Desabilitando el cache, de lo contrario los
		// comentarios se actualizarian solamente cuando
		// un usuario modifique un artículo.
		$parser->disableCache();
	
		if (!$wgUser->isLoggedIn()) return '<div class="formularioComentarios" style="color:#ff0000"><p>debe estar autenticado para colocar un mensaje</p></div>';
		
		$actionUrl = SpecialPage::getTitleFor( 'NuevoComentario' )->getLocalURL();
		
		
		$content .= '<div class="formularioComentarios">';
		$content .=    '<form action="' . $actionUrl . '">';
		$content .=       '<fieldset>';
		$content .=          '<legend>Comparta comentario</legend>';
		$content .=          '<ul>';
		$content .=             '<li>';
		$content .=                '<label for="usr_nombre">Nombre:</label>';
		$content .=                '<input type="text" name="usr_nombre" id="usr_nombre" value="' . mysql_real_escape_string($wgUser->getName()) . '" disabled="disabled" />';
		$content .=             '</li>';
		$content .=             '<li>';
		$content .=                '<input type="hidden" name="pageTitle" value="' . $wgTitle . '" />';
		$content .=                '<label for="texto_comentario">Comentario:</label>';
		$content .=                '<textarea rows="5" cols="20" name="comentario" id="comentario"></textarea>';
		$content .=             '</li>';
		$content .=          '</ul>';
		$content .=       '</fieldset>';
		$content .=       '<input type="submit" value="Comentar" />';
		$content .=    '</form>';
		$content .= '</div>';
		
		return $content;
		
	}

	function renderList(&$parser){
		
		$content .= '<div class="listaComentarios">';
		$content .= '<h3>Comentarios:</h3>';
		$content .=    '<div class="comentario">';
		$content .=       '<strong>Fulanito</strong> dijo el ' . date(DATE_RFC822) . ': <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin aliquet lacus, id malesuada enim.</span>';
		$content .=    '</div>';
		$content .=    '<div class="comentario">';
		$content .=       '<strong>Fulanito 2</strong> dijo el ' . date(DATE_RFC822) . ': <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sollicitudin aliquet lacus, id malesuada enim.</span>';
		$content .=    '</div>';
		$content .= '</div>';
		
		return $content;
	}

}