<?php 

namespace controllers;

class Magasines extends Controller {

	protected $modelName = \models\Magasines::class;

	public function index(){
		$pageTitle = "Test magazines";
		$style = "css/test.css";
		\Renderer::render('templates/magasines/index', 'templates/', compact('pageTitle', 'style'));
	}
}