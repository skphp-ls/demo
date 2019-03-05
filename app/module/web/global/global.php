<?php
// 生成卡号
function build_card_no(){
	$key = get_global('authkey');
    $rnd = str_replace('.', '', uniqid('', true));
    $rnd = substr(md5($rnd . $key),8,16);
    $rnd = base_convert($rnd, 16, 10);
    return $rnd;
}