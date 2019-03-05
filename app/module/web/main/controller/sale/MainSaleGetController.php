<?php
class MainSaleGetController extends MyGetController
{ 
	const CART_KEY_NAME		=		'pro_sale_cart';
	/**
	新增购物车 
	**/
	public function addCart(){
		$id = $this->requestAbs('id');
		$n = $this->requestAbs('n');
		//Cookie::delCookie('pro_cart');
		$cart = Cookie::getCookie(self::CART_KEY_NAME);
		if($cart)
		{
			$cart = json_decode($cart, true);
		}
		if(isset($cart[$id]))
		{
			$cart[$id]+= $n;
		}else{
			$cart[$id] = $n;
		}
		Cookie::setCookie(self::CART_KEY_NAME, json_encode($cart));
	}
	
	// 减
	public function reduceCart()
	{
		$id = $this->requestAbs('id');
		$n = $this->requestAbs('n');
		//Cookie::delCookie('pro_cart');
		$cart = Cookie::getCookie(self::CART_KEY_NAME);
		if($cart)
		{
			$cart = json_decode($cart, true);
		}
		if(isset($cart[$id]))
		{
			if($cart[$id] > $n) $cart[$id]-= $n;
		}
		Cookie::setCookie(self::CART_KEY_NAME, json_encode($cart));
	}

	// 删
	public function delCart()
	{
		$id = $this->requestAbs('id');
		$cart = Cookie::getCookie(self::CART_KEY_NAME);
		if($cart)
		{
			$cart = json_decode($cart, true);
			if(isset($cart[$id]))
			{
				unset($cart[$id]);
				if(count($cart) > 0) 
				{
					Cookie::setCookie(self::CART_KEY_NAME, json_encode($cart));
				}else{
					Cookie::delCookie(self::CART_KEY_NAME);
				}
			}else{
				Cookie::delCookie(self::CART_KEY_NAME);
			}
		}
	}
}