<?php
class SocketClass{
	
	private $socket;

    function __construct($sockettype = 'socket')
    {
		$this->socket = socket_create(AF_INET , SOCK_STREAM , getprotobyname ('tcp'));
		socket_connect ($this->socket , HOST , PORT); 
    } 

    public function getData($byte, $args = null)
    {
    	$ret = '' ;
		$tmp_len = 0 ;	
		$content = '' ;
		do
		{
			$tmp_len += socket_recv ($this->socket , $content , ($byte - $tmp_len) , MSG_WAITALL);
			$ret .= $content ;
		} 
		while ($tmp_len < $byte) ; 
		return is_null ($args) ? $ret : unpack ($args , $ret) ;		  	
    }

	public function sendData($msg){
		socket_send($this->socket , $msg , strlen($msg), 0) ;
	}
}

class FsockopenClass
{
	private $socket;

    function __construct()
    {
 		$this->socket = fsockopen(HOST, PORT, $errno, $errstr, STIMEOUT);
		stream_set_timeout($this->socket, STIMEOUT)
	}	

    public function getData($byte, $args = null)
    {
		$ret = fread ($this->socket , $byte);
		if($ret == false){
			return false;
		}
		return is_null ($args) ? $ret : unpack ($args , $ret) ;		  	
    }

	public function sendData($msg)
	{
		fwrite($this->socket , $msg) ;
	}
}