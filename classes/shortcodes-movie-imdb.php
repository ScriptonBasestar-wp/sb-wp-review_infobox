<?php
/**
 * https://www.imdb.com/title/tt2446980/?ref_=hm_fanfav_tt_2_pd_fp1
 * http://www.omdbapi.com/?i=tt2446980&plot=short&r=json
 * 
 * [sb_movie_infobox_from_imdb id=tt2446980]
 */


function fn_sb_movie_infobox_from_imdb( $atts ) {
    extract( shortcode_atts( array('id' => 0, 'detailType' => 'short' ), $atts, 'cwktag' ) );

    if( !$id ) {
        return "Movie id null or unknown error : " . $id;
    }
    if( !$detailType) {
        $detailType = 'short';
    }

    $cacheage = get_option('sbcacheage', -1);
    $api_client_id = get_option('api_imdb_client_id', null);
    $api_client_secret = get_option('api_imdb_client_secret', null);
    if( !$api_client_id || !$api_client_secret ) {
        return "api_imdb_client_id, api_imdb_client_secret is required";
    }

    $imageCacheDir = SB_CACHE_DIR."/imdb/{$id}.jpg";
    $imageCacheUrl = SB_CACHE_URL."/imdb/{$id}.jpg";
    // $imageCacheUrl = "{SB_CACHE_URL}/{$this -> moduleName}/{$id}.jpg";
    $jsonCacheDir = SB_CACHE_DIR."/imdb/{$id}.json";

    $dataJson = fn_sb_movie_infobox_cache($id, $detailType, $imageCacheDir, $jsonCacheDir, $cacheage, $api_client_id, $api_client_secret);

    $outHtml =
"
<div class='shortcodes-movie-infobox-wrapper'>
    <span class='shortcodes-movie-infobox-title'>제목 : {$json["Title"]}</span>
    <div class='shortcodes-movie-infobox-poster' ><img src='{$imageCacheUrl}'/></div>
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
    return $outHtml;
}

function fn_sb_movie_infobox_cache($id, $detailType, $imageCacheDir, $jsonCacheDir, $cacheage, $api_client_id, $api_client_secret)
{
    if (
        !file_exists($imageCacheDir) || ($cacheage > -1 && filemtime($imageCacheDir) < (time() - $cacheage)) ||
        !file_exists($jsonCacheDir) || ($cacheage > -1 && filemtime($jsonCacheDir) < (time() - $cacheage))
    ) {
        // from web
        $url = "https://www.omdbapi.com/?i={$id}&plot={$detailType}&r=json";
        $http_args = array(
            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)'
        );
        $rawResponse = wp_remote_request($url, $http_args);
        $rawResponse = $rawResponse['body'];
        // $rawResponse = file_get_contents_curl($url);

        _ = file_put_contents($jsonCacheDir, $rawResponse);
        $dataJson = json_decode($rawResponse, true);

        $dataImage = file_get_contents_curl($dataJson['Poster']);
        _ = file_put_contents($imageCacheDir, $dataImage);
    } else {
        $rawResponse = file_get_contents($jsonCacheDir);
        $dataJson = json_decode($rawResponse, true);
    }
    return $dataJson;
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

add_shortcode( 'sb_movie_infobox_from_imdb', 'fn_sb_movie_infobox_from_imdb');