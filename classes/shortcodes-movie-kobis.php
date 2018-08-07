<?php
/**
 * http://www.kobis.or.kr/kobisopenapi/homepg/apiservice/searchServiceInfo.do
 *
 */

// Shortcode Example
// [sb_movie_infobox_from_naver id=tt2446980]

function fn_sb_movie_infobox_from_kobis( $atts ) {
    extract(shortcode_atts( array('id' => 0, 'detailType' => 'short' ), $atts, 'cwktag' ));

    if( !$id ) {
        return "Movie id null or unknown error : " . $id;
    }
    if( !$detailType) {
        $detailType = 'short';
    }

    $json = fn_sb_movie_infobox_cache_from_naver($id, $detailType);

    $out =
"
<div class='shortcodes-movie-infobox-wrapper'>
    <span class='shortcodes-movie-infobox-title'>제목 : {$json["Title"]}</span>
    <div class='shortcodes-movie-infobox-poster' ><img src='{$json["Poster"]}'/></div>
    <div class='shortcodes-movie-infobox-description-wrapper'>
        <div>제작년도 : {$json["Year"]}</div>
        <div>개봉일 : {$json["Released"]}</div>
        <div>상영시간 : {$json["Runtime"]}</div>
        <div>장르 : {$json["Genre"]}</div>
        <div>감독 : {$json["Director"]}</div>
        <div>언어 : {$json["Language"]}</div>
        <div>국가 : {$json["Country"]}</div>
    </div>
</div>
";
//  return 'SB movie infobox operation failed: ' . $response->get_error_message();
    return $out;
}

function fn_sb_movie_infobox_cache($id, $detailType)
{
    $cacheage = get_option('sbcacheage', -1);

    $imageCacheDir = SB_CACHE_DIR."/kobis/{$id}.jpg";
    $imageCacheUrl = SB_CACHE_URL."/kobis/{$id}.jpg";
    $jsonCacheDir = SB_CACHE_DIR."/kobis/{$id}.json";

    if (
        !file_exists($imageCacheDir) || ($cacheage > -1 && filemtime($imageCacheDir) < (time() - $cacheage)) ||
        !file_exists($jsonCacheDir) || ($cacheage > -1 && filemtime($jsonCacheDir) < (time() - $cacheage))
    ) {
        $url = "http://www.kobis.or.kr/kobisopenapi/webservice/rest/movie/searchMovieList.json?key=d9d4dd7891200b8a8217717e9fdbfd24&movieNm={$id}";
        $http_args = array(
            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)'
        );
        $rawResponse = wp_remote_request($url, $http_args);
        $rawResponse = $rawResponse['body'];
//        $raw = file_get_contents_curl('http://www.omdbapi.com/?i=' . $id."&plot=short&r=json");

        $jsonResult = file_put_contents($jsonCacheDir, $rawResponse);
        $json = json_decode($rawResponse, true);
//        echo("jsonResult". $jsonResult . "<br/>");
        $img = file_get_contents_curl($json['Poster']);
        $jsonResult = file_put_contents($imageCacheDir, $img);
        $json['Poster'] = $imageCacheUrl;
    }else{
        $rawResponse = file_get_contents($jsonCacheDir);
        $json = json_decode($rawResponse, true);
        $json['Poster'] = $imageCacheUrl;
    }
    return $json;
}

function file_get_contents_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.imdb.com/');
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

add_shortcode( 'sb_movie_infobox_from_kobis', 'fn_sb_movie_infobox_from_kobis');
