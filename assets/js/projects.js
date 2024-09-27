/* global document, gpProjectIcon */

jQuery( document ).ready( function( $ ) {
	// Get the Translation Sets table.
	var projects = $( '.gp-content #' + gpProjectIcon.args.projects_list_id + ' dl dt' );

	// Get the Project.
	var projectIconURLs = gpProjectIcon.args.project_icon_urls;

	// Get the Base URL for GlotPress Projects.
	var gpUrlProject = gpProjectIcon.gp_url_project;

	console.log( 'Loaded projects.js' );

	// Loop through all the project list items.
	$( projects ).each(
		function() {
			// Add the icon to each row.
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
					$( this ).html( '<span class="icon"><img src="' + projectIconURL + '"></span><span class="name">' + projectName + '</span>' );
				}
			);
		}
	);
} );
