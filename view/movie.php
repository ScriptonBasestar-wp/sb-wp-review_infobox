<?php
/**
 * @author archmagece
 * @since 2015-12-24 00:57
 */
$out =
'<div style="display: inline-block; width:100%; min-width:500px; height:300px;border: solid coral 1px;">
        <span style="display: inline-block; width:100%;float:left;">���� : $json["Title"]</span>
        <img src="$json["Poster"]" style="display: block; width:49%;min-width:150px;height:300px;float:left;border: solid coral 1px;"/>
        <div style="display: block; width:49%;min-width:200px;height:300px;float:right;border: solid coral 1px;">
            <span style="display: inline-block; width:100%;float:left;">�󿵳⵵ : $json["Year"]</span>
            <span style="display: inline-block; width:100%;float:left;">�󿵽����� : $json["Released"]</span>
            <span style="display: inline-block; width:100%;float:left;">�󿵽ð� : $json["Runtime"]</span>
            <span style="display: inline-block; width:100%;float:left;">�帣 : $json["Genre"]</span>
            <span style="display: inline-block; width:100%;float:left;">���� : $json["Director"]</span>
            <span style="display: inline-block; width:100%;float:left;">��� : $json["Language"]</span>
            <span style="display: inline-block; width:100%;float:left;">�������� : $json["Country"]</span>
        </div>
    </div>
';
echo($out);

?>