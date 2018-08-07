<?php
/**
 * http://movie.naver.com/movie/bi/mi/basic.nhn?code=52747
 *
 */

// Shortcode Example
// [sb_movie_infobox_from_naver id=tt2446980]

function fn_sb_movie_infobox_from_naver( $atts ) {
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

function fn_sb_movie_infobox_cache_from_naver($id, $detailType)
{
    $cacheage = get_option('sbcacheage', -1);

    $imageCacheDir = SB_CACHE_DIR."/naver/{$id}.jpg";
    $imageCacheUrl = SB_CACHE_URL."/naver/{$id}.jpg";
    $jsonCacheDir = SB_CACHE_DIR."/naver/{$id}.json";

    if (
        !file_exists($imageCacheDir) || ($cacheage > -1 && filemtime($imageCacheDir) < (time() - $cacheage)) ||
        !file_exists($jsonCacheDir) || ($cacheage > -1 && filemtime($jsonCacheDir) < (time() - $cacheage))
    ) {
        $url = "http://movie.naver.com/movie/bi/mi/basic.nhn?code={$id}";
        $http_args = array(
            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)'
        );
        $rawResponse = wp_remote_request($url, $http_args);
        $rawResponse = $rawResponse['body'];
//        $raw = file_get_contents_curl('http://www.omdbapi.com/?i=' . $id."&plot=short&r=json");

        $current_charset = get_bloginfo('charset');
//        $doc = phpQuery::newDocumentHTML($rawResponse, $current_charset);
        $doc = phpQuery::newDocumentHTML($rawResponse);
        phpQuery::selectDocument($doc);

        $json['Title'] = pq("#content > div.article > div.mv_info_area > div.mv_info > h3 > a:nth-child(1)")->html();
        $json['Poster'] = pq("#content > div.article > div.mv_info_area > div.poster > a > img")->attr('src');
        $json['Year'] = pq("#content > div.article > div.mv_info_area > div.mv_info > strong")->html();
        $json['Released'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(4)")->html();
        $json['Runtime'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(3)")->html();
        $json['Genre'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(1)")->html();
        $json['Director'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(4) > p > a")->html();
        $json['Language'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(2) > a")->html();
        $json['Country'] = pq("#content > div.article > div.mv_info_area > div.mv_info > dl > dd:nth-child(2) > p > span:nth-child(2) > a")->html();


        $img = file_get_contents_curl_from_naver($json['Poster']);
        $jsonResult = file_put_contents($imageCacheDir, $img);
        $json['Poster'] = $imageCacheUrl;
    }else{
        $rawResponse = file_get_contents($jsonCacheDir);
        $json = json_decode($rawResponse, true);
        $json['Poster'] = $imageCacheUrl;
    }
    return $json;
}

function file_get_contents_curl_from_naver($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.naver.com/');
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