<?php 

class Http {

	/**
	* Redirige le visiteur sur $url
	*
	* @param string $url
	* @return void
	*/
	public static function redirect(string $url) : void {
		header("Location: $url");
		exit();
	}
}