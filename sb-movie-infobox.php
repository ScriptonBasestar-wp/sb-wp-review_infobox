<?php
/*
* Plugin Name: SB-Movie-Infobox
* Plugin URI: http://scriptonbasestar.com/wp/plugin
* Description: ScriptonBasestar Inc. wordpress movie plugin.
* Version: 0.1
* Text Domain: sb-movie-infobox
* Domain Path: /languages/
* Author: archmagece
* Author URI: http://scriptonbasestar.com
* License: GPLv3
* License URI: http://www.gnu.org/licenses/gpl-3.0
* Slug: sb-movie-infobox
*/

define('SB_MOVIE_INFOBOX_ROOT_URL', WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/');
define('SB_MOVIE_INFOBOX_ROOT_PATH', str_replace("\\", "/", WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/'));

$upload_dir = wp_upload_dir();
define('SB_IMAGE_CACHE_DIR', $upload_dir['basedir'] . "/sb_cache");
define('SB_IMAGE_CACHE_URL', $upload_dir['baseurl'] . "/sb_cache");

//include("classes/filter-imdb-button.php");
include("classes/shortcodes-imdb.php");

?>