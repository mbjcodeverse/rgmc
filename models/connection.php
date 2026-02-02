<?php

class Connection{
	public function connect(){
		// $link = new PDO("mysql:host=localhost;dbname=rgmc", "root", "");

		// $link = new PDO("mysql:host=localhost;dbname=u896983687_rgmc", "u896983687_rgmc", "RGMC_onwards2025");

		// rivsongoldplast.com
		$link = new PDO("mysql:host=localhost;dbname=u604140403_rgmc", "u604140403_rgmc", "RGMC_onwards2025");

		$link -> exec("set names utf8");
		return $link;
	}
}