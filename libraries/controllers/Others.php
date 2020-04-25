<?php

namespace controllers;

class Others extends Controller {

	protected $modelName = \models\Others::class;

	public function cgu(){
		$pageTitle = "Conditions Générales d'Utilisation";
		$style = 'css/commentaires.css';
		$ligne = file_get_contents('templates/staff/administration/fichiers-txt/cgu.txt', FILE_USE_INCLUDE_PATH);
		\Renderer::render('templates/others/cgu', 'templates/', compact('pageTitle', 'style', 'ligne'));
	}

	public function partenaires(){
		$pageTitle = "Partenaires du site";
		$style = 'css/commentaires.css';
		$ligne = file_get_contents('templates/staff/administration/fichiers-txt/partenaires.txt', FILE_USE_INCLUDE_PATH);
		\Renderer::render('templates/others/partenaires', 'templates/', compact('pageTitle', 'style', 'ligne'));
	}

	public function faq(){
		$pageTitle = "Foire aux questions";
		$style = 'css/commentaires.css';
		$ligne = file_get_contents('templates/staff/administration/fichiers-txt/faq.txt', FILE_USE_INCLUDE_PATH);
		\Renderer::render('templates/others/faq', 'templates/', compact('pageTitle', 'style', 'ligne'));
	}

	public function changelog(){
		$pageTitle = "Mises à jour du site";
		$style = "css/commentaires.css";
		$changelog = $this->model->changelog();
		\Renderer::render('templates/others/changelog', 'templates/', compact('pageTitle', 'style', 'changelog'));
	}
}