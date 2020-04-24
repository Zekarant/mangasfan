<?php

namespace Controllers;

class Others extends Controller {

	protected $modelName = \Models\Others::class;

	public function cgu(){
		$pageTitle = "Conditions Générales d'Utilisation";
		$style = 'css/commentaires.css';
		$ligne = file_get_contents('templates/staff/administration/fichiers-txt/cgu.txt', FILE_USE_INCLUDE_PATH);
		\Renderer::render('templates/others/cgu', 'templates/', compact('pageTitle', 'style', 'ligne'));
	}

	public function partenaires(){
		$pageTitle = "Partenaires du site";
		$style = 'css/commentaires.css';
		$ligne = file_get_contents('templates/staff/administration/fichiers-text/partenaires.txt', FILE_USE_INCLUDE_PATH);
		\Renderer::render('templates/others/partenaires', 'templates/', compact('pageTitle', 'style', 'ligne'));
	}

	public function faq(){
		$pageTitle = "Foire aux questions";
		$style = 'css/commentaires.css';
		$ligne = file_get_contents('templates/staff/administration/fichiers-txt/faq.txt', FILE_USE_INCLUDE_PATH);
		\Renderer::render('templates/others/faq', 'templates/', compact('pageTitle', 'style', 'ligne'));
	}
}