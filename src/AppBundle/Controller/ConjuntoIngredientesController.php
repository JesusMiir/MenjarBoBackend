<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Conjuntoingredientes;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class ConjuntoIngredientesController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Conjunto de Ingredientes";
		die();
	}

}