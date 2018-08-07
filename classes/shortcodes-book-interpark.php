<?php
/**
 * http://book.interpark.com/api/search.api?key=E7510CBA0498562C8F7D45CBAFC6B7718C0C0BBEB491C9B5145A2BB11B7E54B7&query=9788992717199&queryType=isbn&output=json
 *
 */

// Shortcode Example
// [sb_book_infobox_from_interpark id=9788992717199]

function fn_sb_book_infobox_from_interpark( $atts ) {
    extract(shortcode_atts( array('id' => 0, 'detailType' => 'short' ), $atts, 'cwktag' ));

    if( !$id ) {
        return "id null or unknown error : " . $id;
    }
    if( !$detailType) {
        $detailType = 'short';
    }

    $json = fn_sb_book_infobox_cache_from_interpark($id, $detailType);

    $out =
"
<div class='shortcodes-book-infobox-wrapper'>
    <span class='shortcodes-book-infobox-title'>제목 : {$json["title"]}</span>
    <div class='shortcodes-book-infobox-poster' ><img src='{$json["coverLargeUrl"]}'/></div>
    <div class='shortcodes-book-infobox-description-wrapper'>
        <div>출판일 : {$json["pubDate"]}</div>
        <div>저자 : {$json["author"]}</div>
        <div>역자 : {$json["translator"]}</div>
        <div>isbn : {$json["isbn"]}</div>
        <div>출판사 : {$json["publisher"]}</div>
    </div>
    <div><a href='{$json["link"]}'> 구매하기</a></div>
</div>
";
//  return 'SB review infobox operation failed: ' . $response->get_error_message();
    return $out;
}

function fn_sb_book_infobox_cache_from_interpark($id, $detailType)
{
    $interparkkey = get_option('sbinterparkkey', -1);
    $cacheage = get_option('sbcacheage', -1);

    $imageCacheDir = SB_CACHE_DIR."/interpark/{$id}.jpg";
    $imageCacheUrl = SB_CACHE_URL."/interpark/{$id}.jpg";
    $jsonCacheDir = SB_CACHE_DIR."/interpark/{$id}.json";

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
//        $raw = file_get_contents_curl('http://www.omdbapi.com/?i=' . $id."&plot=short&r=json");

        $jsonResult = file_put_contents($jsonCacheDir, $rawResponse);
        $json = json_decode($rawResponse, true);
        $json = $json["item"][0];
        $img = file_get_contents_curl($json['coverLargeUrl']);

        $jsonResult = file_put_contents($imageCacheDir, $img);
        $json['coverLargeUrl'] = $imageCacheUrl;
    }else{
        $rawResponse = file_get_contents($jsonCacheDir);
        $json = json_decode($rawResponse, true);
        $json = $json["item"][0];
        $json['coverLargeUrl'] = $imageCacheUrl;
    }
    return $json;
}

function file_get_contents_curl_from_interpark($url)
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