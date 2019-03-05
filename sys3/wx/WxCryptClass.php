<?php
/**
 * 加密消息
 */
include_once "crypt/wxBizMsgCrypt.php";
class WxCrypt
{
	// 解密
	public static function decryptMsg($from_xml)
	{
		$msg_xml = '';	
		$arr_data = AppIdConf::$arr_signature;
		//save_log("arr_data.txt", 'arr_data:' . json_encode($arr_data) . "\n");y
		if($arr_data['encrypt_type'] == 'aes')
		{
			$conf = AppIdConf::Conf();
			$wc = new WXBizMsgCrypt($conf['TOKEN'], $conf['EncodingAESKey'], $conf['AppId']);
			$err_code = $wc->decryptMsg($arr_data['msg_signature'], $arr_data['timestamp'], $arr_data['nonce'], $from_xml, $msg_xml);
			//save_log("xml.txt", 'err_code:' . $err_code . ';msg_xml:' . $msg_xml. "\n");
			$msg_xml = simplexml_load_string($msg_xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
		}else{
			$msg_xml = simplexml_load_string($from_xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		}
		return (array)$msg_xml;
	}

	// 加密
	public static function encryptMsg($msg_xml)
	{
		$from_xml = '';		
		$arr_data = AppIdConf::$arr_signature;
		if($arr_data['encrypt_type'] == 'aes')
		{	
			$conf = AppIdConf::Conf();	
			$wc = new WXBizMsgCrypt($conf['TOKEN'], $conf['EncodingAESKey'], $conf['AppId']);
			$wc->encryptMsg($msg_xml, $arr_data['timestamp'], $arr_data['nonce'], $from_xml);
		}else{
			$from_xml = $msg_xml;
		}
		return $from_xml;
	}	
}