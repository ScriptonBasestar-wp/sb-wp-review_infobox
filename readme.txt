=== ScriptonBasestar Movie Infobox===

Contributors: archmagece
Donate link: http://scriptonbasestar.com/contribute/
Tags: imdb, naver, daum, movie, shortcode, film, cinema,
Requires at least: 4.4
Tested up to: 4.4
Stable tag: 0.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Display movie information from IMDB(or ...) in wordpress post.

== Description ==
This plugin helps to add movie information (from IMDB) in wordpress post using shortcode [sb_movie_infobox_from_imdb id="tt2446980"].
**IMDB Info Box** is using [omdbapi.com](http://www.omdbapi.com) API which provides information from Imdb.

Read more how this plugin works http://99webtools.com/blog/php-get-movie-information-from-imdb/

= Note =
This plugin is not endorsed by or affiliated with IMDb.com

== Installation ==
Using the Plugin Manager

1. Click Plugins
2. Click Add New
3. Search for `sb-movie-infobox`
4. Click Install
5. Click Install Now
6. Click Activate Plugin

Manually

1. Upload `sb-movie-infobox` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. IMDB Info box post demo
2. IMDB Info Box settings page


== Frequently Asked Questions ==

= Why cache is necessary? =

Cache is crucial to `IMDB Info Box` plugin. As first imdb searchs are quite time consuming, if you do not want to kill your server but instead want quickest browsing experience, you should use cache.

= How to display full plot on movie? =

To display full plot use `plot` attribute eg
`[sb_movie_infobox_from_imdb id="tt0910970"]` default short
`[sb_movie_infobox_from_imdb id="tt0910970" detailType="short"]` for short plot (default)
`[sb_movie_infobox_from_imdb id="tt0910970" detailType="full"]` for full plot


== Changelog ==

= 0.1 =
* not finished
