/* global document, wp */

jQuery( document ).ready( function( $ ) {
	var mediaUploader; // Variable for the wp.media mediaUploader

	var projectIconSelect = $( '#project-icon-button-select' );
	var projectIconClear = $( '#project-icon-button-clear' );
	var projectIconAttachmentID = $( '#icon_attachment_id' );

	console.log( 'Loaded project-edit.js' );

	// Image select button clicked.
	$( projectIconSelect ).on( 'click', function( event ) {
		openMediaUploader( event );
	} );

	// Image clear button clicked.
	$( projectIconClear ).on( 'click', function( event ) {
		event.preventDefault();

		// Clear the image preview by removing the src attribute.
		$( projectIconSelect ).css( 'background-image', 'none' );

		// Clear the value of the icon_attachment_id hidden input.
		$( projectIconAttachmentID ).attr( 'value', '' );

		// Hide the Clear button.
		$( projectIconClear ).hide();

		// Show and focus on the button label text.
		$( projectIconSelect ).css( 'color', 'var( --gp-color-btn-text )' ).focus();
	} );

	/**
	 * Open the media select file modal.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} event : Click event.
	 */
	function openMediaUploader( event ) {
		event.preventDefault();
		console.log( event );

		// If the mediaUploader has already been created, just reuse it.
		if ( mediaUploader ) {
			mediaUploader.open();
			return;
		}

		mediaUploader = wp.media.frames.mediaUploader = wp.media( {
			title: wp.i18n.__( 'Select your Project Icon', 'gp-project-icon' ), // Default: title: $( this ).data( 'uploader_title' )
			button: {
				text: wp.i18n.__( 'Select Icon', 'gp-project-icon' ), // Default: text: $( this ).data( 'uploader_button_text' )
			},
			multiple: false, // Set to true for multiple file selection.
		} );

		mediaUploader.on( 'select', function() {
			var attachment = mediaUploader.state().get( 'selection' ).first().toJSON();

			// Set the image preview by adding the URL to the src attribute.
			$( projectIconSelect ).css( 'background-image', 'url(' + attachment.url + ')' );

			// Set the value of the icon_attachment_id hidden input.
			$( projectIconAttachmentID ).attr( 'value', attachment.id );

			// Show the Clear button.
			$( projectIconClear ).show();

			// Hide the button label.
			$( projectIconSelect ).css( 'color', 'transparent' );
		} );

		mediaUploader.open();
	}
} );
