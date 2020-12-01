<?php
/**
 * http://www.kobis.or.kr/kobisopenapi/webservice/rest/movie/searchMovieInfo.json?key=430156241533f1d058c603178cc3ca0e&movieCd=20124079
 *
 * [sb_movie_infobox_from_kobis id=tt2446980]
 */


function fn_sb_movie_infobox_from_kobis( $atts ) {
    extract(shortcode_atts( array('id' => 0, 'detailType' => 'short' ), $atts, 'cwktag' ));

    if( !$id ) {
        return "Movie id null or unknown error : " . $id;
    }
    if( !$detailType) {
        $detailType = 'short';
    }

    $cacheage = get_option('sbcacheage', -1);
    $api_client_key = get_option('api_kobis_client_key', null);
    if( !$api_client_key ) {
        return "api_kobis_client_key is required";
    }

    $imageCacheDir = SB_CACHE_DIR."/kobis/{$id}.jpg";
    $imageCacheUrl = SB_CACHE_URL."/kobis/{$id}.jpg";
    // $imageCacheUrl = "{SB_CACHE_URL}/{$this -> moduleName}/{$id}.jpg";
    $jsonCacheDir = SB_CACHE_DIR."/kobis/{$id}.json";

    $dataJson = fn_sb_movie_infobox_cache($id, $detailType, $imageCacheDir, $jsonCacheDir, $cacheage, $api_client_key);

    $outHtml =
"
<div class='shortcodes-movie-infobox-wrapper'>
    <span class='shortcodes-movie-infobox-title'>제목 : {$dataJson["Title"]}</span>
    <div class='shortcodes-movie-infobox-poster' ><img src='{$imageCacheUrl}'/></div>
    <div class='shortcodes-movie-infobox-description-wrapper'>
        <div>제작년도 : {$dataJson["Year"]}</div>
        <div>개봉일 : {$dataJson["Released"]}</div>
        <div>상영시간 : {$dataJson["Runtime"]}</div>
        <div>장르 : {$dataJson["Genre"]}</div>
        <div>감독 : {$dataJson["Director"]}</div>
        <div>언어 : {$dataJson["Language"]}</div>
        <div>국가 : {$dataJson["Country"]}</div>
    </div>
</div>
";
    return $outHtml;
}

function fn_sb_movie_infobox_cache($id, $detailType, $imageCacheDir, $jsonCacheDir, $cacheage, $api_key)
{
    if (
        !file_exists($imageCacheDir) || ($cacheage > -1 && filemtime($imageCacheDir) < (time() - $cacheage)) ||
        !file_exists($jsonCacheDir) || ($cacheage > -1 && filemtime($jsonCacheDir) < (time() - $cacheage))
    ) {
        $url = "http://www.kobis.or.kr/kobisopenapi/webservice/rest/movie/searchMovieInfo.json?key={$api_key}&movieCd={$id}"
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
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.kobis.or.kr/');
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