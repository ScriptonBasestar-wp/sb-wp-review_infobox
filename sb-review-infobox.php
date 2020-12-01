<?php
/*
* Plugin Name: SB-Review-Infobox
* Plugin URI: http://scriptonbasestar.com/wp/plugin
* Description: ScriptonBasestar Inc. wordpress review info plugin. usage : [sb_review_infobox_from_imdb id="imdb id"]
* Version: 0.2
* Text Domain: sb-review-infobox
* Domain Path: /languages/
* Author: archmagece
* Author URI: http://scriptonbasestar.org
* License: GPLv3
* License URI: http://www.gnu.org/licenses/gpl-3.0
* Slug: sb-review-infobox
*/

defined('ABSPATH') or die("No direct script access allowed.");

define('SB_REVIEW_INFOBOX_ROOT_URL', WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/');
define('SB_REVIEW_INFOBOX_ROOT_PATH', str_replace("\\", "/", WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)) . '/'));

$upload_dir = wp_upload_dir();
define('SB_CACHE_DIR', $upload_dir['basedir'] . "/sb_cache");
define('SB_CACHE_URL', $upload_dir['baseurl'] . "/sb_cache");

require_once plugin_dir_path(__FILE__) . ''

//include("classes/filter-imdb-button.php");
include("classes/shortcodes-imdb.php");



function sb_review_infobox_activate()
{
//    add_option('imdbcacheage', -1);
//    add_option('imdbheadbg', 'FFCC00');
//    add_option('imdbheadfg', 'FFFFFF');
//    add_option('imdbbodybg', 'F4F3D9');
//    add_option('imdbbodyfg', '333333');
//    add_option('imdbcorner', '5');
    if (!is_dir(SB_CACHE_DIR)) {
        if (!wp_mkdir_p(SB_CACHE_DIR)) {
            die("Unable to create cache directory in uploads folder. Please make sure uploads directory is writable");
        }
    }
}

function sb_review_infobox_deactivate()
{
//    delete_option('imdbcacheage');
//    delete_option('imdbheadbg');
//    delete_option('imdbheadfg');
//    delete_option('imdbbodybg');
//    delete_option('imdbbodyfg');
//    delete_option('imdbcorner');
}

register_activation_hook(__FILE__, 'sb_review_infobox_activate');
register_deactivation_hook(__FILE__, 'sb_review_infobox_deactivate');

?>