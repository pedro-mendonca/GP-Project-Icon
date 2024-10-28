# GP Project Icon

Add icons to your GlotPress projects.

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/gp-project-icon?label=Plugin%20Version&logo=wordpress)](https://wordpress.org/plugins/gp-project-icon/)
[![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/stars/gp-project-icon?label=Plugin%20Rating&logo=wordpress)](https://wordpress.org/support/plugin/gp-project-icon/reviews/)
[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/gp-project-icon.svg?label=Downloads&logo=wordpress)](https://wordpress.org/plugins/gp-project-icon/advanced/)
[![Sponsor](https://img.shields.io/badge/GitHub-🤍%20Sponsor-ea4aaa?logo=github)](https://github.com/sponsors/pedro-mendonca)

[![WordPress Plugin Required PHP Version](https://img.shields.io/wordpress/plugin/required-php/gp-project-icon?label=PHP%20Required&logo=php&logoColor=white)](https://wordpress.org/plugins/gp-project-icon/)
[![WordPress Plugin: Required WP Version](https://img.shields.io/wordpress/plugin/wp-version/gp-project-icon?label=WordPress%20Required&logo=wordpress)](https://wordpress.org/plugins/gp-project-icon/)
[![WordPress Plugin: Tested WP Version](https://img.shields.io/wordpress/plugin/tested/gp-project-icon.svg?label=WordPress%20Tested&logo=wordpress)](https://wordpress.org/plugins/gp-project-icon/)

[![GlotPress Plugin: Required GP Version](https://img.shields.io/badge/GlotPress%20Required-v4.0.0-826eb4.svg)](https://wordpress.org/plugins/glotpress/)
[![GlotPress Plugin: Tested GP Version](https://img.shields.io/badge/GlotPress%20Tested-v4.0.1-826eb4.svg)](https://github.com/GlotPress/GlotPress/releases/tag/4.0.1)

[![Coding Standards](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/coding-standards.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/coding-standards.yml)
[![Static Analysis](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/static-analysis.yml)
[![WP Plugin Check](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/plugin-check.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/plugin-check.yml)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/4ac97db27f2245fcbed4a17fc6d333ea)](https://app.codacy.com/gh/pedro-mendonca/GP-Project-Icon/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## Description

This GlotPress plugin allows you to add icons to your projects.

Up to GlotPress v4.0.1 the templates 'project.php' and 'projects.php' don't have the necessary hooks to easily add the icons on server side.

For now the icons are added on the frontend with JavaScript.

If the PR [Add action hooks and filters to Projects and Project templates](https://github.com/GlotPress/GlotPress/pull/1864) is merged, then the JS is no longer needed and the template customization will be done the right way.

The plugin uses `gp_meta` with the meta key `project_icon` to store the ID of the media library attachment, for each object of type `project` with a corresponding ID.

## Features

* GlotPress projects page:
  * Icons on the left of the project names.
* GlotPress project page:
  * Header icon above the project title.
  * Icons on the left of the sub-project names.
* GlotPress project edit/new form:
  * Media file select field to choose an image from the media library.

## Known issues

* Currently, GlotPress still does not delete meta when deleting a project, leading to orphaned meta.
* GlotPress does not clone the meta when branching a project, so any cloned projects do not inherit its icon.

## Requirements

* GlotPress v4.0.

## Frequently Asked Questions

### Can I contribute to this plugin?

Sure! You are welcome to report any issues or add feature suggestions on the [GitHub repository](https://github.com/pedro-mendonca/GP-Project-Icon).

## Screenshots

1. Projects page with added icons

   ![screenshot-1](./.wordpress-org/screenshot-1.png)

2. Project page with header icon and sub-project icons

   ![screenshot-2](./.wordpress-org/screenshot-2.png)

3. Project page with header icon and translation sets

   ![screenshot-3](./.wordpress-org/screenshot-3.png)

4. Project page with header icon and sub-project icons

   ![screenshot-4](./.wordpress-org/screenshot-4.png)

5. Project edit/new form with no icon selected

   ![screenshot-5](./.wordpress-org/screenshot-5.png)

6. Project edit/new form selecting an icon from the media library

   ![screenshot-6](./.wordpress-org/screenshot-6.png)

7. Project edit/new form with icon selected

   ![screenshot-7](./.wordpress-org/screenshot-7.png)

## Changelog

### Unreleased

* Tested up to WP 6.7.
* Include minified assets maps.
* Add prefixes for supported browserslist.

### 1.0.0

* Initial release.
* Add project icons to GlotPress Projects page.
* Add project header icon to GlotPress Project page.
* Add sub-project icons to GlotPress Project page.
* Add media file select field to the Project edit/new form.
* Delete project icon meta when deleting a project.
