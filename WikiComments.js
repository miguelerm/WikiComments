/**
 * Regresa el fomulario de comentarios a su estado original, eliminando
 * el texto que se habia indicado como texto citado para una respuesta. 
 */
function cancelarRespuesta(){
	$('.formularioComentarios').children().find('[name=parentId]').val(0);
	$('#reply').hide();
	$('#replytext').val("");
	$('#text').focus();
}

/**
 * Muestra el texto que se va a responder en el formulario de comentarios.
 * @param id Identificador único del comentario que se quiere responder.
 */
function responder(id){
	$('.formularioComentarios').children().find('[name=parentId]').val(id);
	$('#reply').show();
	$('#replytext').val($('#comment' + id + 'text').text());
	$('#text').focus();
}