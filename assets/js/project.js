/* global document, gpProjectIcon */

jQuery( document ).ready( function( $ ) {
	// Get the Translation Sets table.
	var projects = $( '.gp-content #projects dl dt' );

	// Get the Project.
	var projectIconURLs = gpProjectIcon.args.project_icon_urls;

	// Get the Base URL for GlotPress Projects.
	var gpUrlProject = gpProjectIcon.gp_url_project;

	// Set the data attrib prefix.
	//var dataPrefix = 'gpprojecticondata-';

	console.log( 'Loaded projects.js' );

	console.log( projectIconURLs );

	console.log( gpUrlProject );

	// Add attributes 'gpprojecticon-' to each row.
	$( projects ).each(
		function() {
			// Add attributes 'gptoolboxdata-' to each row.
			$( this ).children( 'a:first-child' ).each(
				function() {
					// Create a regular expression pattern to find the Project Path of the Project row.
					var regexPattern = new RegExp( '^' + gpUrlProject + '(.*)/$' );

					/**
					 * Check for Project path in the link.
					 * Example: ../glotpress/projects/plugins/hello-dolly/
					 */
					var match = $( this ).attr( 'href' ).match( regexPattern );
					var projectPath = match[1]; // 'plugin/hello-dolly'.
					var projectName = $( this ).text();
					var projectIconURL = projectIconURLs[projectPath];
					if ( projectIconURL === false ) {
						projectIconURL = '';
					}

					// Add the Project Icon.
					// $( this ).closest( 'dt' ).attr( dataPrefix + 'projectpath', projectPath );
					$( this ).html( '<span class="icon"><img src="' + projectIconURL + '" width=32 height=32></span><span class="name">' + projectName + '</span>' );
				}
			);
		}
	);
} );
