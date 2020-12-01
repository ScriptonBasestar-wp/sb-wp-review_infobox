<?php
/**
 * https://movie.naver.com/movie/bi/mi/basic.nhn?code=52747
 * https://openapi.naver.com/v1/search/movie.json?
 *
 * [sb_movie_infobox_from_naver id=52747]
 */


function fn_sb_movie_infobox_from_naver( $atts ) {
    extract(shortcode_atts( array('id' => 0, 'detailType' => 'short' ), $atts, 'cwktag' ));

    if( !$id ) {
        return "Movie id null or unknown error : " . $id;
    }
    if( !$detailType) {
        $detailType = 'short';
    }

    $cacheage = get_option('sbcacheage', -1);
    $api_client_id = get_option('api_naver_client_id', null);
    $api_client_secret = get_option('api_naver_client_secret', null);

    $imageCacheDir = SB_CACHE_DIR."/naver/{$id}.jpg";
    $imageCacheUrl = SB_CACHE_URL."/naver/{$id}.jpg";
    // $imageCacheUrl = "{SB_CACHE_URL}/{$this -> moduleName}/{$id}.jpg";
    $jsonCacheDir = SB_CACHE_DIR."/naver/{$id}.json";

    $dataJson = fn_sb_movie_infobox_cache($id, $detailType, $imageCacheDir, $jsonCacheDir, $cacheage, $api_client_id, $api_client_secret);

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

function fn_sb_movie_infobox_cache($id, $detailType, $imageCacheDir, $jsonCacheDir, $cacheage, $api_client_id, $api_client_secret)
{
    if (
        !file_exists($imageCacheDir) || ($cacheage > -1 && filemtime($imageCacheDir) < (time() - $cacheage)) ||
        !file_exists($jsonCacheDir) || ($cacheage > -1 && filemtime($jsonCacheDir) < (time() - $cacheage))
    ) {
        // from web
        $url = "https://movie.naver.com/movie/bi/mi/basic.nhn?code={$id}";
        $http_args = array(
            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)'
        );
        $rawResponse = wp_remote_request($url, $http_args);
        $rawResponse = $rawResponse['body'];

        // $current_charset = get_bloginfo('charset');
//        $doc = phpQuery::newDocumentHTML($rawResponse, $current_charset);
        $doc = phpQuery::newDocumentHTML($rawResponse);
        phpQuery::selectDocument($doc);

        $dataJson['Title'] = pq("#content > div.article > div.mv_info_area > div.mv_info > h3 > a:nth-child(1)")->html();
        $dataJson['Poster'] = pq("#content > div.article > div.mv_info_area > div.poster > a > img")->attr('src');
        $dataJson['Year'] = pq("#content > div.article > div.mv_info_area > div.mv_info > strong")->html();
        $dataJson['Released'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(4)")->html();
        $dataJson['Runtime'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(3)")->html();
        $dataJson['Genre'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(1)")->html();
        $dataJson['Director'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(4) > p > a")->html();
        $dataJson['Language'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(2) > a")->html();
        $dataJson['Country'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(2) > a")->html();

        $dataImage = file_get_contents_curl_from_naver($dataJson['Poster']);
        $imageResult = file_put_contents($imageCacheDir, $dataImage);
        $jsonResult = file_put_contents($jsonCacheDir, $dataJson);
        // from api
        // $raw = file_get_contents_curl('https://openapi.naver.com/v1/search/movie.json?' . $id."&plot=short&r=json");
    }else{
        $rawResponse = file_get_contents($jsonCacheDir);
        $dataJson = json_decode($rawResponse, true);
    }
    return $dataJson;
}

function file_get_contents_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.naver.com/');
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

add_shortcode( 'sb_movie_infobox_from_naver', 'fn_sb_movie_infobox_from_naver');