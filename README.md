# University Theme

A custom WordPress theme designed for educational institutions like universities and colleges. This theme provides a modern, flexible, and easy-to-use platform for creating websites for academic purposes.

## Features

- Custom post types for Campus, Events, Programs, Professors
- Template files for specific content types (e.g., single-event.php, archive-campus.php)
- Dynamic front-page template
- Integrated search functionality
- Custom styles and layouts for educational content
- Responsive design for mobile and tablet support

## Installation

1. Download or clone this repository into your `/wp-content/themes/` directory.
2. In your WordPress admin dashboard, go to **Appearance** > **Themes**.
3. Activate the "University Theme".

## Development Setup

To work with the theme's development environment:

1. Install Node.js and npm (Node Package Manager).
2. Run `npm install` in the theme directory to install the required packages.
3. Use `npm run dev` to start the local development server with live reload.

## Customization

- You can modify the theme’s appearance by editing the CSS files in the `css` directory or creating custom styles in the `style.css`.
- Templates in the `template-parts` folder can be modified for custom layouts.
- The `functions.php` file includes essential theme functions and WordPress hooks for extended functionality.

## Folder Structure

- `archive-*.php`: Template files for custom post types (Events, Programs, etc.)
- `single-*.php`: Single post templates for custom post types
- `css/`: Contains the stylesheets for the theme
- `images/`: Image assets for the theme
- `inc/`: Includes additional PHP scripts for extended functionality
- `src/`: Source files for JavaScript and styles

## License

This theme is open-source and available under the GPL license.