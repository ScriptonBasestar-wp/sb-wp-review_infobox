<?php
/**
 * @author archmagece
 * @since 2015-12-24 03:02
 */

namespace sbReviewInfobox;

function fn_sb_movie_imdb_button( $original ) {
    if ( ! is_array( $this->options ) ) {
        $this->load_settings();
    }

    $buttons_2 = $this->toolbar_2;

    if ( is_array( $original ) && ! empty( $original ) ) {
        $original = array_diff( $original, $this->buttons_filter );
        $buttons_2 = array_merge( $buttons_2, $original );
    }

    return $buttons_2;
}

add_filter( 'sb_movie_imdb_button', 'fn_sb_movie_imdb_button' );