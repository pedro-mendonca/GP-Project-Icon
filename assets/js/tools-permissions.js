/* global confirm, document, gpToolbox, Intl, wp, sprintf, wpApiSettings */

jQuery( document ).ready( function( $ ) {
	// Get User login (username).
	var userLogin = gpToolbox.user_login;

	// Get User Locale.
	var userLocale = gpToolbox.user_locale;

	// Get the Admin permissions table.
	var gpToolboxTablePermissionAdmin = $( 'table.gp-table.gp-project-icon.tools-permission-admin' );

	// Get the Validator permissions table.
	var gpToolboxTablePermissionValidator = $( 'table.gp-table.gp-project-icon.tools-permission-validator' );

	// Check if user is has GlotPress Admin previleges.
	var glotpressAdmin = gpToolbox.admin;

	// Set the data attrib prefix.
	var dataPrefix = 'gptoolboxdata-';

	// Set tables rows.
	var permissionAdminsRows = $( gpToolboxTablePermissionAdmin ).find( 'tbody tr' );
	var permissionValidatorsRows = $( gpToolboxTablePermissionValidator ).find( 'tbody tr' );

	// Check if the Permission Validators table exist.
	if ( gpToolboxTablePermissionAdmin.length ) {
		// Capture button click.
		$( gpToolboxTablePermissionAdmin ).children( 'tbody' ).children( 'tr' ).each(
			function() {
				var permissionID = $( this ).attr( 'gptoolboxdata-permission' );

				// Check if user has GlotPress administrator previleges.
				if ( glotpressAdmin ) {
					// Check if current user matches the row user to avoid remove self admin previleges.
					if ( $( this ).find( 'td.user' ).text().trim() === userLogin ) {
						// Disable delete button.
						$( this ).find( 'td.action button.delete' ).attr( 'disabled', true );
					} else {
						// Delete permission.
						$( this ).find( 'td.action button.delete' ).on( 'click', function() {
							// Confirmation before delete.
							if ( confirm( wp.i18n.__( "You are about to permanently delete these permission.\nThis action cannot be undone.\n'Cancel' to stop, 'OK' to delete.", 'gp-project-icon' ) ) ) {
								permissionDelete( 'admin', permissionID );
							}
						} );
					}
				}
			}
		);
	}

	// Check if the Permission Validators table exist.
	if ( gpToolboxTablePermissionValidator.length ) {
		// Capture button click.
		$( gpToolboxTablePermissionValidator ).children( 'tbody' ).children( 'tr' ).each(
			function() {
				var permissionID = $( this ).attr( 'gptoolboxdata-permission' );

				// Check if user has GlotPress administrator previleges.
				if ( glotpressAdmin ) {
					// Delete permission.
					$( this ).find( 'td.action button.delete' ).on( 'click', function() {
						// Confirmation before delete.
						if ( confirm( wp.i18n.__( "You are about to permanently delete these permission.\nThis action cannot be undone.\n'Cancel' to stop, 'OK' to delete.", 'gp-project-icon' ) ) ) {
							permissionDelete( 'validator', permissionID );
						}
					} );
				}
			}
		);
	}

	console.log( 'Loaded Tools JS' );

	/**
	 * Delete permission with specified ID.
	 *
	 * @param {string} permissionType : Type of the GP_Permission.
	 * @param {string} permissionID   : ID of the GP_Permission.
	 */
	function permissionDelete( permissionType, permissionID ) {
		// Find the table cell from any permissions table.
		var tr = $( 'table.gp-table.gp-project-icon.tools-permission-' + permissionType ).find( 'tbody tr[' + dataPrefix + 'permission="' + permissionID + '"]' );
		var td = $( tr ).find( 'td.action' );

		var notice = $( td ).find( 'div.progress-notice' );
		var button = $( td ).find( 'button.delete' );
		var bubble = $( td ).find( 'span.duplicate' );

		console.log( 'Deleting:', permissionType, permissionID );

		// Hide and disable button.
		$( button ).hide().attr( 'disabled', true );
		// Hide duplicate bubble.
		$( bubble ).hide();
		// Show progress notice.
		$( notice ).text( wp.i18n.__( 'Deleting...', 'gp-project-icon' ) ).fadeIn();

		$.ajax( {

			url: wpApiSettings.root + 'gp-project-icon/v1/permissions/' + permissionID + '/-delete',
			type: 'POST',
			data: {
				_wpnonce: gpToolbox.nonce,
			},

			success: function( response ) {
				var permissionsCount = $( 'p#permission-' + permissionType + '-count span.count' ).text();

				// Check response.
				if ( response.deleted === true ) {
					// console.log( 'TR:', tr );
					$( tr ).fadeOut();
					permissionsCount = permissionsCount - 1;

					if ( permissionsCount > 0 ) {
						// Update permissions count.
						$( 'p#permission-' + permissionType + '-count' ).html(
							sprintf(
								/* translators: %s: Permissions count. */
								wp.i18n._n(
									'%s Permission found.',
									'%s Permissions found.',
									permissionsCount,
									'gp-project-icon'
								),
								'<span class="count">' + new Intl.NumberFormat( userLocale.slug ).format( permissionsCount ) + '</span>'
							)
						);
					} else {
						// Update permissions count to 0 and hide Filter and Table.
						$( 'p#permission-' + permissionType + '-count' ).text( wp.i18n.__( 'No permissions found.', 'gp-project-icon' ) );
						// Hide table filter.
						$( 'div.permission-' + permissionType + '-filter' ).fadeOut();
						// Hide table.
						$( 'table.gp-table.gp-project-icon.tools-permission-' + permissionType ).fadeOut();
					}

					console.log( response.message );
				} else {
					// Hide progress notice.
					$( notice ).hide().text( '' );
					// Show progress notice.
					$( notice ).text(
						wp.i18n.sprintf(
							/* translators: %s Error message. */
							wp.i18n.__( 'Error: %s', 'gp-project-icon' ),
							response.message
						)
					);
					$( notice ).fadeIn();
					console.log( response.message );
				}
			},

			error: function( response ) {
				// Show the Error notice.
				console.log( 'Failed to delete permission.' );
				console.log( 'Error message:', response.responseJSON.message );
			},

			complete: function() {
				// Hide progress notice.
				// $( notice ).hide().text( '' );
			},
		} );
	}

	// Configure Tablesorter.
	$( gpToolboxTablePermissionAdmin ).tablesorter( {
		theme: 'glotpress',
		sortList: [
			[ 1, 0 ],
		],
		headers: {
			0: {
				sorter: 'text',
			},
		},
	} );

	// Table search.
	$( '#permission-admin-filter' ).bind( 'change keyup input', function() {
		var words = this.value.toLowerCase().split( ' ' );

		if ( '' === this.value.trim() ) {
			permissionAdminsRows.show();
		} else {
			permissionAdminsRows.hide();
			permissionAdminsRows.filter( function() {
				var t = $( this );
				var d;
				for ( d = 0; d < words.length; ++d ) {
					if ( t.text().toLowerCase().indexOf( words[d] ) !== -1 ) {
						return true;
					}
				}
				return false;
			} ).show();
		}
	} );

	// Configure Tablesorter.
	$( gpToolboxTablePermissionValidator ).tablesorter( {
		theme: 'glotpress',
		sortList: [
			[ 1, 0 ],
			[ 2, 0 ],
			[ 3, 0 ],
		],
		headers: {
			0: {
				sorter: 'text',
			},
		},
	} );

	// Table search.
	$( '#permission-validator-filter' ).bind( 'change keyup input', function() {
		var words = this.value.toLowerCase().split( ' ' );

		if ( '' === this.value.trim() ) {
			permissionValidatorsRows.show();
		} else {
			permissionValidatorsRows.hide();
			permissionValidatorsRows.filter( function() {
				var t = $( this );
				var d;
				for ( d = 0; d < words.length; ++d ) {
					if ( t.text().toLowerCase().indexOf( words[d] ) !== -1 ) {
						return true;
					}
				}
				return false;
			} ).show();
		}
	} );

	// Clear table filter.
	$( 'button#permission-admin-filter-clear' ).click( function() {
		// Clear the text input filter.
		$( 'input#permission-admin-filter' ).val( '' );
		// Show all rows.
		$( gpToolboxTablePermissionAdmin ).find( 'tbody tr' ).show();
	} );

	// Clear table filter.
	$( 'button#permission-validator-filter-clear' ).click( function() {
		// Clear the text input filter.
		$( 'input#permission-validator-filter' ).val( '' );
		// Show all rows.
		$( gpToolboxTablePermissionValidator ).find( 'tbody tr' ).show();
	} );
} );
