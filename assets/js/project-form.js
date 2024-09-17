/* global  document, Intl, gpToolbox, wp, wpApiSettings */

console.log( 'Project Forms loaded.' );
jQuery( document ).ready( function( $ ) {
	var mediaUploader;

	$( '#project-icon-button' ).click( function( e ) {
		e.preventDefault();
		// If the media uploader already exists, reopen it.
		if ( mediaUploader ) {
			mediaUploader.open();
			return;
		}

		// Create a new media uploader.
		mediaUploader = wp.media( {
			title: 'Select Project Icon',
			button: {
				text: 'Use this icon',
			},
			multiple: false,
		} );

		// When an image is selected, grab the URL and set it as the value of the hidden input.
		mediaUploader.on( 'select', function() {
			var attachment = mediaUploader.state().get( 'selection' ).first().toJSON();
			$( '#project-icon' ).val( attachment.url );
			$( '#project-icon-preview' ).attr( 'src', attachment.url );
		} );

		// Open the uploader dialog.
		mediaUploader.open();
	} );

	$( '#project-icon-remove' ).click( function( e ) {
		e.preventDefault();
		$( '#project-icon' ).val( '' );
		$( '#project-icon-preview' ).attr( 'src', '' );
	} );
} );
