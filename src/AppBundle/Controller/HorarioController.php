<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Horario;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

// CANVIAR

class HorarioController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Horario";
		die();
	}

	public function nuevoAction(Request $request, $id = null) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$json = $request->get("json", null);
		$params = json_decode($json);

		if ($json != null) {
			
			$nombre = (isset($params->nombre)) ? $params->nombre : null;

			$lunesM = (isset($params->lunesM)) ? $params->lunesM : null;
			$lunesT = (isset($params->lunesT)) ? $params->lunesT : null;

			$martesM = (isset($params->martesM)) ? $params->martesM : null;
			$martesT = (isset($params->martesT)) ? $params->martesT : null;
			
			$miercolesM = (isset($params->miercolesM)) ? $params->miercolesM : null;
			$miercolesT = (isset($params->miercolesT)) ? $params->miercolesT : null;
			
			$juevesM = (isset($params->juevesM)) ? $params->juevesM : null;
			$juevesT = (isset($params->juevesT)) ? $params->juevesT : null;

			$viernesM = (isset($params->viernesM)) ? $params->viernesM : null;
			$viernesT = (isset($params->viernesT)) ? $params->viernesT : null;

			$sabadoM = (isset($params->sabadoM)) ? $params->sabadoM : null;
			$sabadoT = (isset($params->sabadoT)) ? $params->sabadoT : null;

			$domingoM = (isset($params->domingoM)) ? $params->domingoM : null;
			$domingoT = (isset($params->domingoT)) ? $params->domingoT : null;

			$restaurante_id = (isset($params->idRestaurante)) ? $params->idRestaurante : null;

			$em = $this->getDoctrine()->getManager();
			$restaurante = $em->getRepository("BackendBundle:Restaurante")->findOneBy(array(
						"id" => $restaurante_id
					));

			if ($restaurante != null) {

				if($id == null) {
					$horario = new Horario();
					$horario->setRestaurante($restaurante);
					
					$horario->setLunesM($lunesM);
					$horario->setLunesT($lunesT);

					$horario->setMartesM($martesM);
					$horario->setMartesT($martesT);

					$horario->setMiercolesM($miercolesM);
					$horario->setMiercolesT($miercolesT);

					$horario->setJuevesM($juevesM);
					$horario->setJuevesT($juevesT);

					$horario->setViernesM($viernesM);
					$horario->setViernesT($viernesT);

					$horario->setSabadoM($sabadoM);
					$horario->setSabadoT($sabadoT);

					$horario->setDomingoM($domingoM);
					$horario->setDomingoT($domingoT);
					
					$em->persist($horario);
					$em->flush();
				
					$data = array(
						'status' => 'success',
						'code' => 200,
						'msg' => 'Nuevo Horario creado!!',
						'horario' => $horario
					);
				}
				else {
					$horario = $em->getRepository("BackendBundle:Horario")->findOneBy(array(
						"restaurante" => $id
					));

					$token = $request->get('authorization', null);
					$authCheck = $jwt_auth->checkToken($token);
					$identity = $jwt_auth->checkToken($token, true);

					if (isset($identity->id) && $identity->id == $horario->getRestaurante()->getId()) {
						
						
						$horario->setRestaurante($restaurante);
						
						$horario->setLunesM($lunesM);
						$horario->setLunesT($lunesT);

						$horario->setMartesM($martesM);
						$horario->setMartesT($martesT);

						$horario->setMiercolesM($miercolesM);
						$horario->setMiercolesT($miercolesT);

						$horario->setJuevesM($juevesM);
						$horario->setJuevesT($juevesT);

						$horario->setViernesM($viernesM);
						$horario->setViernesT($viernesT);

						$horario->setSabadoM($sabadoM);
						$horario->setSabadoT($sabadoT);

						$horario->setDomingoM($domingoM);
						$horario->setDomingoT($domingoT);

						$em->persist($horario);
						$em->flush();

						$data = array(
							'status' => 'success',
							'code' => 200,
							'msg' => 'Modificación Horario echa!!',
							'horario' => $horario
						);

					}
					else {
						$data = array(
							'status' => 'error',
							'code' => 400,
							'msg' => 'Error en la modificación Horario, no eres el propietario!!'
						);
					}
				}
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Horario no creado, parámetros incorrectos!!'
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


	public function listaAction(Request $request) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();

			$dql = "SELECT h FROM BackendBundle:Horario h WHERE h.restaurante = {$identity->id} ORDER BY h.id DESC";
			$query = $em->createQuery($dql);

			$pagina = $request->query->getInt('pagina', 1);
			$paginador = $this->get('knp_paginator');
			$items_por_pagina = 10;

			$paginacion = $paginador->paginate($query, $pagina, $items_por_pagina);
			$total_items_contador = $paginacion->getTotalItemCount();

			$data = array(
				'status' => 'success',
				'code' => 200,
				'total_items_contador' => $total_items_contador,
				'pagina_actual' => $pagina,
				'items_por_pagina' => $items_por_pagina,
				'total_paginas' => ceil($total_items_contador/$items_por_pagina),
				'data' => $paginacion
			);
		}
		else {
			$data = array(
				'status' => 'error',
				'code' => 400,
				'msg' => 'Autorización no valida'
			);
		}
		return $helpers->json($data);
	}

	public function informacionAction(Request $request, $id = null) {

		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$correcto = true;

		$em = $this->getDoctrine()->getManager();
		
		if($id == null) {
			$authCheck = true;
			$restaurante = $em->getRepository('BackendBundle:Restaurante')->findOneBy(array(
				'nombre' => 'Ingredients'
			));
		}
		else {

			$token = $request->get('authorization', null);
			$authCheck = $jwt_auth->checkToken($token);
			$identity = $jwt_auth->checkToken($token, true);

			$restaurante = $em->getRepository('BackendBundle:Restaurante')->findOneBy(array(
				'id' => $id
			));

			$correcto = ($restaurante->getId() == $identity->id);
		}


		if($restaurante && is_object($restaurante) && $correcto) {
			
			$horario = $em->getRepository('BackendBundle:Horario')->findOneBy(array(
				'restaurante' => $restaurante
			));

			if ($horario && is_object($horario)) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"horario" => $horario
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Horario no encontrado'
				);
			}
		}
		else {
			$data = array(
				'status' => 'error',
				'code' => 400,
				'msg' => 'Restaurante no encontrado'
			);
		}

		return $helpers->json($data);
	}

	public function eliminarAction(Request $request, $id = null) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();
			$horario = $em->getRepository('BackendBundle:Horario')->findOneBy(array(
				'id' => $id
			));

			if($horario && is_object($horario) && $identity->id == $horario->getRestaurante()->getId()) {
				// Borrar objeto y borrar registro de la tabla bbdd
				$em->remove($horario);
				$em->flush();

				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $horario
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Horario no encontrado'
				);
			}
		}
		else {
			$data = array(
				'status' => 'error',
				'code' => 400,
				'msg' => 'Autorización no válida'
			);
		}

		return $helpers->json($data);
	}

}