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
http://99webtools.com/blog/php-get-movie-information-from-imdb/
추가로 한개 더 퍼옴
https://github.com/WPDevelopers/embedpress
이건 퍼온건 아니고 사용
https://github.com/ahmadawais/create-guten-block

= Note =
리뷰대상 정보 보여주는 플러그인

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
`[sb_book_infobox_from_naver id=tt2446980]`
`[sb_movie_infobox_from_imdb id=tt2446980]`
`[sb_movie_infobox_from_naver id=tt2446980]`

More option
`[sb_review_infobox_from_imdb id="tt0910970" detailType="short"]` for short plot (default)
`[sb_review_infobox_from_imdb id="tt0910970" detailType="full"]` for full plot

