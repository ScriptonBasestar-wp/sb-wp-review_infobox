=== ScriptonBasestar Review Infobox ===

Contributors: archmagece
Donate link: http://scriptonbasestar.com/contribute/
Tags: imdb, naver, daum, movie, shortcode, film, cinema,
Requires at least: 4.4
Tested up to: 4.4
Stable tag: 0.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Display movie information in wordpress post.

== Description ==
This plugin helps to add target information for review in wordpress post using shortcode [sb_review_infobox_from_imdb id="tt2446980"].

이거 처음 만들 때 어딘가에서 가져와서 변경한 것 같은데 잘 기억이 안난다. GPLv2~3이었으니 라이센스를 유지한다.
참고:  http://99webtools.com/blog/php-get-movie-information-from-imdb/

= Note =
This plugin is not endorsed by or affiliated with IMDb.com

== Installation ==
Using the Plugin Manager

1. Click Plugins
2. Click Add New
3. Search for `sb-review-infobox`
4. Click Install
5. Click Install Now
6. Click Activate Plugin

Manually

1. Upload `sb-review-infobox` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Review Info box post demo
2. Review Info Box settings page


== Frequently Asked Questions ==

= really? =

no

= How to use? =

To display information in post
`[sb_book_infobox_from_interpark id=9788992717199]`
`[sb_movie_infobox_from_imdb id=tt2446980]`
`[sb_movie_infobox_from_naver id=tt2446980]`
`[sb_movie_infobox_from_naver id=tt2446980]`

More option
`[sb_review_infobox_from_imdb id="tt0910970" detailType="short"]` for short plot (default)
`[sb_review_infobox_from_imdb id="tt0910970" detailType="full"]` for full plot


== Changelog ==

= 0.1 =
* not finished

= 0.2 =
* 영화정보 imdb, naver, kobis
* 도서정보 interpark
* 설정 편집화면 제공
* GPLv3 적용
