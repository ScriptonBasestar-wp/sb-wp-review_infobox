<?php
/**
 * http://www.omdbapi.com/?i=tt2446980&plot=short&r=json
 * Âü°í http://99webtools.com/blog/php-get-movie-information-from-imdb/
 */
// Shortcode Example
// [sb_movie_infobox_from_imdb movieid="tt2446980"]


function fn_sb_movie_infobox_from_imdb( $atts ) {
    extract( shortcode_atts( array('movieid' => 0, 'detailType' => 'simple' ), $atts, 'cwktag' ) );

    echo($movieid);
    if( !$movieid ) {
        return "Movie id null or unknown error : " . $movieid;
    }

    if( !$detailType) {
        $detailType = 'simple';
    }

    $json = fn_sb_movie_infobox_cache($movieid);
    echo("before include");
    include("../view/movie.php");
    echo("after include");

//  return 'SB movie infobox operation failed: ' . $response->get_error_message();
    return $json;
}

function fn_sb_movie_infobox_cache($id)
{
    echo("json start");
//    $cacheage = get_option('imdbcacheage', -1);
    $cacheage = -1;
    $imageCache = IMDBCACHE . "/" . $id . ".jpg";
    $jsonCache = IMDBCACHE . "/" . $id . ".json";

    if (
        !file_exists($imageCache) || ($cacheage > -1 && filemtime($imageCache) < (time() - $cacheage)) ||
        !file_exists($jsonCache) || ($cacheage > -1 && filemtime($jsonCache) < (time() - $cacheage))
    ) {
        //    $url = "http://www.omdbapi.com/?i=".$movieid."&plot=short&r=json";
        $url = "http://www.omdbapi.com/?i={$id}&plot=short&r=json";
        $http_args = array(
            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)'
        );
        $rawResponse = wp_remote_request($url, $http_args);
        $rawResponse = $rawResponse['body'];
//        $raw = file_get_contents_curl('http://www.omdbapi.com/?i=' . $id."&plot=short&r=json");
        $json = json_decode($rawResponse, true);
        echo("<br/>");
        echo($json);
        echo("<br/>");
        echo($json['Poster']);
        echo("<br/>");
        file_put_contents($jsonCache, $json);
        $img = file_get_contents_curl($json['Poster']);
        file_put_contents($imageCache, $img);
        $json['Poster'] = $imageCache;
    }else{
        $rawResponse = file_get_contents($jsonCache);
        $json = json_decode($rawResponse, true);
    }
    echo("json end");
    return $json;
}

function file_get_contents_curl($url)
{
    echo("crl start 111");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.imdb.com/');
    echo("crl start 111");
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    echo("crl start 111");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    echo("crl start 111");
    curl_close($ch);
    echo("crl start 111");
    return $data;
}

add_shortcode( 'sb_movie_infobox_from_imdb', 'fn_sb_movie_infobox_from_imdb');

// Shortcode Example 2
// [cwk-example]Here goes your content.[/cwk-example]


function fn_sb_movie_infobox_example_style( $atts, $content = null ) {
    return '<div classes="cwk-example">' . $content . '</div>';
}

add_shortcode( 'sb_movie_infobox_example_style', 'fn_sb_movie_infobox_example_style');

