<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Conjuntoplatos;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class ConjuntoPlatosController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Conjunto de Platos";
		die();
	}

	public function nuevoAction(Request $request) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if ($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);
			$json = $request->get("json", null);

			if ($json != null) {
				
				$em = $this->getDoctrine()->getManager();

				$params = json_decode($json);

				$idEncargo = (isset($params->idEncargo)) ? $params->idEncargo : null;
				$idPlato = (isset($params->idPlato)) ? $params->idPlato : null;
				$idMenu = (isset($params->idMenu)) ? $params->idMenu : null;
				$idCombo = (isset($params->idCombo)) ? $params->idCombo : null;
				$cantidad = (isset($params->cantidad)) ? $params->cantidad : null;

				$conjuntoPlatos = new Conjuntoplatos();
				
				$encargo = $em->getRepository("BackendBundle:Encargo")->findOneBy(array(
					'id' => $idEncargo
				));
				$conjuntoPlatos->setEncargo($encargo);
				
				if ($idPlato != null) {
					$plato = $em->getRepository("BackendBundle:Plato")->findOneBy(array(
						'id' => $idPlato
					));	
					$conjuntoPlatos->setPlato($plato);
				}
				
				if ($idMenu != null) {
					$menu = $em->getRepository("BackendBundle:Menu")->findOneBy(array(
						'id' => $idMenu
					));	
					$conjuntoPlatos->setPlato($menu);
				}
				
				if ($idCombo != null) {
					$plato = $em->getRepository("BackendBundle:Combo")->findOneBy(array(
						'id' => $idCombo
					));	
					$conjuntoPlatos->setPlato($combo);
				}

				$conjuntoPlatos->setCantidad($cantidad);

				$em->persist($conjuntoPlatos);
				$em->flush();
					
				$data = array(
					'status' => 'success',
					'code' => 200,
					'msg' => 'Nuevo Conjuno Platos creado!!',
					'conjuntoPlatos' => $conjuntoPlatos
				);

			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Conjunto platos no creado!!'
				);
			}
		}
		else {
			$data = array(
				"status" => "error",
				"code" => 400,
				"msg" => "Authorization not valid"
			);
		}

		return $helpers->json($data);
	}

	// Modificar
	public function listaAction(Request $request, $idEncargo = null) {
		$helpers = $this->get(Helpers::class);

		if ($idEncargo != null) {

			$em = $this->getDoctrine()->getManager();
			$conjuntoPlatos = $em->getRepository("BackendBundle:Conjuntoplatos")->findBy(array(
				'encargo' => $idEncargo
			));

			$data = array(
				'status' => 'success',
				'code' => 200,
				'conjuntoPlatos' => $conjuntoPlatos
			);
		}
		else {
			$data = array(
				"status" => "error",
				"code" => 400,
				"msg" => "Encargo not valid"
			);
		}

		return $helpers->json($data);
	}


}