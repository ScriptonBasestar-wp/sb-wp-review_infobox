<?php
/**
 * @author archmagece
 * @since 2015-12-24 00:57
 */
$out =
'<div style="display: inline-block; width:100%; min-width:500px; height:300px;border: solid coral 1px;">
        <span style="display: inline-block; width:100%;float:left;">제목 : $json["Title"]</span>
        <img src="$json["Poster"]" style="display: block; width:49%;min-width:150px;height:300px;float:left;border: solid coral 1px;"/>
        <div style="display: block; width:49%;min-width:200px;height:300px;float:right;border: solid coral 1px;">
            <span style="display: inline-block; width:100%;float:left;">상영년도 : $json["Year"]</span>
            <span style="display: inline-block; width:100%;float:left;">상영시작일 : $json["Released"]</span>
            <span style="display: inline-block; width:100%;float:left;">상영시간 : $json["Runtime"]</span>
            <span style="display: inline-block; width:100%;float:left;">장르 : $json["Genre"]</span>
            <span style="display: inline-block; width:100%;float:left;">감독 : $json["Director"]</span>
            <span style="display: inline-block; width:100%;float:left;">언어 : $json["Language"]</span>
            <span style="display: inline-block; width:100%;float:left;">제조국가 : $json["Country"]</span>
        </div>
    </div>
';
echo($out);

?>