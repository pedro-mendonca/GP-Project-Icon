# GP Project Icon

Set of tools to help you manage your GlotPress.

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/gp-project-icon?label=Plugin%20Version&logo=wordpress)](https://wordpress.org/plugins/gp-project-icon/)
[![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/stars/gp-project-icon?label=Plugin%20Rating&logo=wordpress)](https://wordpress.org/support/plugin/gp-project-icon/reviews/)
[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/gp-project-icon.svg?label=Downloads&logo=wordpress)](https://wordpress.org/plugins/gp-project-icon/advanced/)
[![Sponsor](https://img.shields.io/badge/GitHub-🤍%20Sponsor-ea4aaa?logo=github)](https://github.com/sponsors/pedro-mendonca)

[![WordPress Plugin Required PHP Version](https://img.shields.io/wordpress/plugin/required-php/gp-project-icon?label=PHP%20Required&logo=php&logoColor=white)](https://wordpress.org/plugins/gp-project-icon/)
[![WordPress Plugin: Required WP Version](https://img.shields.io/wordpress/plugin/wp-version/gp-project-icon?label=WordPress%20Required&logo=wordpress)](https://wordpress.org/plugins/gp-project-icon/)
[![WordPress Plugin: Tested WP Version](https://img.shields.io/wordpress/plugin/tested/gp-project-icon.svg?label=WordPress%20Tested&logo=wordpress)](https://wordpress.org/plugins/gp-project-icon/)

[![GlotPress Plugin: Required GP Version](https://img.shields.io/badge/GlotPress%20Required-v3.0.0-826eb4.svg)](https://wordpress.org/plugins/glotpress/)
[![GlotPress Plugin: Tested GP Version](https://img.shields.io/badge/GlotPress%20Tested-v4.0.0-826eb4.svg)](https://github.com/GlotPress/GlotPress/releases/tag/4.0.0)

[![Coding Standards](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/coding-standards.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/coding-standards.yml)
[![Static Analysis](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/static-analysis.yml)
[![WP Plugin Check](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/plugin-check.yml/badge.svg)](https://github.com/pedro-mendonca/GP-Project-Icon/actions/workflows/plugin-check.yml)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/3539ffa2702a4e1a9b7ac7fcfd506169)](https://app.codacy.com/gh/pedro-mendonca/GP-Project-Icon/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## Description

This GlotPress plugin allows you to add icons to your projects.

## Features

* WordPress dashboard:
  * Admin menu link to GlotPress menu item.
  * Admin menu link to the Tools page.
* GlotPress menu:
  * Menu item for GlotPress Tools.
  * Menu item for WordPress dashboard.
* GlotPress project page:
  * Adds 'Old', 'Rejected' and 'Warnings' columns to the project table of Translation Sets.
  * Button to quickly and permanently delete 'Old' and 'Rejected' translations.
* Tools:
  * Permissions - Overview of Admins and Validators, quick delete, duplicates check.
  * Originals - Overview of all Originals for each Project.
  * Glossaries - Overview of Global Glossaries, Project Glossaries and Glossary entries.
  * Translation Sets - Overview of all Translation Sets.
  * Translations - Overview of all Translations, for each Translation Set.

## Requirements

* GlotPress v3.0.

## Frequently Asked Questions

### Can I contribute to this plugin?

Sure! You are welcome to report any issues or add feature suggestions on the [GitHub repository](https://github.com/pedro-mendonca/GP-Project-Icon).

## Screenshots

1. Project page with added 'Old' and 'Rejected' columns, with delete buttons

   ![screenshot-1](./.wordpress-org/screenshot-1.png)

2. Tools

   ![screenshot-2](./.wordpress-org/screenshot-2.png)

3. Tools > Permissions

   ![screenshot-3](./.wordpress-org/screenshot-3.png)

4. Tools > Originals

   ![screenshot-4](./.wordpress-org/screenshot-4.png)

5. Tools > Translation Set

   ![screenshot-2](./.wordpress-org/screenshot-5.png)

6. Tools > Translations

   ![screenshot-3](./.wordpress-org/screenshot-6.png)

7. Tools > Glossaries

   ![screenshot-4](./.wordpress-org/screenshot-7.png)

## Changelog

### 1.0.5

* New: Warnings column on project page.
* New: Hook to updateHighlight of project page table cells from other plugins.
* Fix: CSS issues.
* Tested up to GlotPress 4.0.0

### 1.0.4

* Tested up to WordPress 6.5
* Tested up to GlotPress 4.0.0-rc.1
* Improve tables filter links
* Set dependency header for GlotPress according WP 6.5

### 1.0.3

* Remove link in current breadcrumb item.
* Link translations with active originals.
* Filter translations by unknown translation sets.
* Show originals filters only for existent cases.
* CSS fixes.

### 1.0.2

* Add confirmation before delete items.

### 1.0.1

* Fix Glossaries tools sorting and improve filtering.
* Improve Glossaries tools filters.

### 1.0.0

* Initial release.
* Add columns for Old and Rejected translations in GlotPress Project page.
* Tools overview page.
* Permissions tools page.
* Originals tools page.
* Glossary tools page.
* Translation Sets tools page.
* Translations tools page.
* WordPress dashboard menu items.
* GlotPress menu items.
