<?php
/**
 * http://book.interpark.com/api/search.api?key={API_KEY}&query=9788992717199&queryType=isbn&output=json
 *
 * [sb_book_infobox_from_interpark id=9788992717199]
 */


function fn_sb_book_infobox_from_interpark( $atts ) {
    extract(shortcode_atts( array('id' => 0, 'detailType' => 'short' ), $atts, 'cwktag' ));

    if( !$id ) {
        return "id null or unknown error : " . $id;
    }
    if( !$detailType) {
        $detailType = 'short';
    }

    $cacheage = get_option('sbcacheage', -1);
    $api_client_key = get_option('api_interpark_client_key', null);
    if( !$api_client_key ) {
        return "api_interpark_client_key is required";
    }

    $imageCacheDir = SB_CACHE_DIR."/interpark/{$id}.jpg";
    $imageCacheUrl = SB_CACHE_URL."/interpark/{$id}.jpg";
    // $imageCacheUrl = "{SB_CACHE_URL}/{$this -> moduleName}/{$id}.jpg";
    $jsonCacheDir = SB_CACHE_DIR."/interpark/{$id}.json";

    $dataJson = fn_sb_movie_infobox_cache($id, $detailType, $imageCacheDir, $jsonCacheDir, $cacheage, $api_client_key);

    $outHtml =
"
<div class='shortcodes-book-infobox-wrapper'>
    <span class='shortcodes-book-infobox-title'>제목 : {$dataJson["title"]}</span>
    <div class='shortcodes-book-infobox-poster' ><img src='{$imageCacheUrl}'/></div>
    <div class='shortcodes-book-infobox-description-wrapper'>
        <div>출판일 : {$dataJson["pubDate"]}</div>
        <div>저자 : {$dataJson["author"]}</div>
        <div>역자 : {$dataJson["translator"]}</div>
        <div>isbn : {$dataJson["isbn"]}</div>
        <div>출판사 : {$dataJson["publisher"]}</div>
    </div>
    <div><a href='{$dataJson["link"]}'> 구매하기</a></div>
</div>
";
    return $outHtml;
}

function fn_sb_book_infobox_cache_from_interpark($id, $detailType)
{
    if (
        !file_exists($imageCacheDir) || ($cacheage > -1 && filemtime($imageCacheDir) < (time() - $cacheage)) ||
        !file_exists($jsonCacheDir) || ($cacheage > -1 && filemtime($jsonCacheDir) < (time() - $cacheage))
    ) {
        $url = "http://book.interpark.com/api/search.api?key={$interparkkey}&query={$id}&queryType=isbn&output=json";
        $http_args = array(
            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)'
        );
        $rawResponse = wp_remote_request($url, $http_args);
        $rawResponse = $rawResponse['body'];
        // $rawResponse = file_get_contents_curl($url);

        _ = file_put_contents($jsonCacheDir, $rawResponse);
        $dataJson = json_decode($rawResponse, true);
        $dataJson = $dataJson["item"][0];

        $dataImage = file_get_contents_curl($dataJson['coverLargeUrl']);
        _ = file_put_contents($imageCacheDir, $dataImage);
    } else {
        $rawResponse = file_get_contents($jsonCacheDir);
        $dataJson = json_decode($rawResponse, true);
        $dataJson = $dataJson["item"][0];
    }
    return $dataJson;
}

function file_get_contents_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.interpark.com/');
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

add_shortcode( 'sb_book_infobox_from_interpark', 'fn_sb_book_infobox_from_interpark');