/* global document, wp */

jQuery( document ).ready( function( $ ) {
	var fileframe; // Variable for the wp.media fileframe

	var projectIconSelect = $( '#frontend-button' );
	var projectIconClear = $( '#frontend-button-clear' );
	var projectIconPreview = $( '#frontend-image' );
	var projectIconAttachmentID = $( '#image_attachment_id' );

	console.log( 'Loaded project-edit.js' );

	// attach a click event (or whatever you want) to some element on your page
	$( projectIconSelect ).on( 'click', function( event ) {
		event.preventDefault();
		console.log( event );

		// if the fileframe has already been created, just reuse it
		if ( fileframe ) {
			fileframe.open();
			return;
		}

		fileframe = wp.media.frames.fileframe = wp.media( {
			title: $( this ).data( 'uploader_title' ),
			button: {
				text: $( this ).data( 'uploader_button_text' ),
			},
			multiple: false, // set this to true for multiple file selection
		} );

		fileframe.on( 'select', function() {
			var attachment = fileframe.state().get( 'selection' ).first().toJSON();

			// Set the image preview by adding the URL to the src attribute.
			$( projectIconPreview ).attr( 'src', attachment.url );

			// Set the value of the image_attachment_id hidden input.
			$( projectIconAttachmentID ).attr( 'value', attachment.id );

			// Show the Clear button.
			$( projectIconClear ).show();

			// Hide the Select button.
			// $( projectIconSelect ).hide();
		} );

		fileframe.open();
	} );

	// attach a click event (or whatever you want) to some element on your page
	$( projectIconClear ).on( 'click', function( event ) {
		event.preventDefault();

		// Clear the image preview by removing the src attribute.
		$( projectIconPreview ).attr( 'src', '' );

		// Clear the value of the image_attachment_id hidden input.
		$( projectIconAttachmentID ).attr( 'value', '' );

		// Hide the Clear button.
		$( projectIconClear ).hide();

		// Show the Select button.
		// $( projectIconSelect ).show();
	} );
} );
