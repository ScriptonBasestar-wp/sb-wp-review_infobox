<?php
/**
 * @author archmagece
 * @since 2015-12-29 01:13
 */


abstract class ShortcodesBase
{
	private $moduleName;
	private $cacheage;

	function __construct($moduleName, $cacheage) {
		$this -> moduleName = $moduleName;
		$this -> cacheage = $cacheage;
	}

	public function printInfobox( $atts ) {
		extract( shortcode_atts( array('id' => 0, 'detailType' => 'short' ), $atts, 'cwktag' ) );

		if( !$id ) {
			return "Movie id null or unknown error : " . $id;
		}
		if( !$detailType) {
			$detailType = 'short';
		}

		$json = cache($id, $detailType);

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

	public function cache($id, $detailType)
	{
		$cacheage = get_option('sbcacheage', -1);
//		$cacheage = $this -> cacheage;

		$imageCacheDir = "{SB_CACHE_DIR}/{$this -> moduleName}/{$id}.jpg";
		$imageCacheUrl = "{SB_CACHE_URL}/{$this -> moduleName}/{$id}.jpg";
		$jsonCacheDir = "{SB_CACHE_DIR}/{$this -> moduleName}/{$id}.json";

		if (
			!file_exists($imageCacheDir) || ($cacheage > -1 && filemtime($imageCacheDir) < (time() - $cacheage)) ||
			!file_exists($jsonCacheDir) || ($cacheage > -1 && filemtime($jsonCacheDir) < (time() - $cacheage))
		) {
			//$url = "http://www.omdbapi.com/?i=".$movieid."&plot=short&r=json";
			$url = "http://www.omdbapi.com/?i={$id}&plot={$detailType}&r=json";
			$http_args = array(
				'user-agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)'
			);
			$rawResponse = wp_remote_request($url, $http_args);
			$rawResponse = $rawResponse['body'];
//        $raw = file_get_contents_curl('http://www.omdbapi.com/?i=' . $id."&plot=short&r=json");

			$jsonResult = file_put_contents($jsonCacheDir, $rawResponse);
			$json = json_decode($rawResponse, true);
//        echo("jsonResult". $jsonResult . "<br/>");
			$img = loadFileUsingCurl($json['Poster']);
			$jsonResult = file_put_contents($imageCacheDir, $img);
			$json['Poster'] = $imageCacheUrl;
		}else{
			$rawResponse = file_get_contents($jsonCacheDir);
			$json = json_decode($rawResponse, true);
			$json['Poster'] = $imageCacheUrl;
		}
		return $json;
	}

	public function loadFileUsingCurl($url)
	{
		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_REFERER, 'http://www.imdb.com/');
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
}