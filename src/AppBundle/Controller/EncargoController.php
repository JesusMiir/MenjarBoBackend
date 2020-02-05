<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Encargo;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class EncargoController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Encargo";
		die();
	}


	public function nuevoAction(Request $request, $email = null) {
		$helpers = $this->get(Helpers::class);
		
		$em = $this->getDoctrine()->getManager();

		$usuario = $em->getRepository("BackendBundle:Usuario")->findOneBy(array(
						"email" => $email
					));

		if ($usuario != null) {
			$restaurante = $em->getRepository("BackendBundle:Restaurante")->findOneBy(array(
				"nombre" => "Ingredients"
			));

			$encargo = new Encargo();
			$encargo->setEstado('Nou');
			$encargo->setInformacionusuario('');
			$encargo->setInformacionrestaurante('');
			$encargo->setPreciototal(0);
			$encargo->setVivienda('Recollir');
			$encargo->setEfectivo(true);
			$encargo->setRestaurante($restaurante);
			$encargo->setUsuario($usuario);

			$em->persist($encargo);
			$em->flush();
					
			$data = array(
						'status' => 'success',
						'code' => 200,
						'msg' => 'Nuevo Encargo creado!!',
						'encargo' => $encargo
					);

		}
		else {
			$data = array(
				"status" => "error",
				"code" => 400,
				"msg" => "Usuario no encontrado"
			);
		}

		return $helpers->json($data);
	}

	public function editarAction(Request $request) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);
			$json = $request->get("json", null);

			if ($json != null) {
				$params = json_decode($json);
				$createdAt = new \Datetime("now");

				$id = (isset($params->id)) ? $params->id : null;
				$estado = (isset($params->estado)) ? $params->estado : null;
				$informacionUsuario = (isset($params->informacionUsuario)) ? $params->informacionUsuario : null;
				$informacionRestaurante = (isset($params->informacionRestaurante)) ? $params->informacionRestaurante : null;
				$precioTotal = (isset($params->precioTotal)) ? $params->precioTotal : null;
				$vivienda = (isset($params->vivienda)) ? $params->vivienda : true;
				$efectivo = (isset($params->efectivo)) ? $params->efectivo : true;

				$em = $this->getDoctrine()->getManager();

				$encargo = $encargo = $em->getRepository("BackendBundle:Encargo")->findOneBy(array(
							"id" => $id
						));

				if ($encargo != null) {

					$encargo->setEstado($estado);
					$encargo->setInformacionusuario($informacionUsuario);
					$encargo->setInformacionrestaurante($informacionRestaurante);
					$encargo->setPreciototal($precioTotal);
					$encargo->setVivienda($vivienda);
					$encargo->setEfectivo($efectivo);
					if ($estado == 'Realitzat') $encargo->setCreateat($createdAt);

					$em->persist($encargo);
					$em->flush();
				
					$data = array(
						'status' => 'success',
						'code' => 200,
						'msg' => 'Editar Encargo echo!!',
						'encargo' => $encargo
					);

				}
				else {
					$data = array(
						"status" => "error",
						"code" => 400,
						"msg" => "Encargo no creado, validación incorrecta!!"
					);
				}
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Encargo no creado, parámetros incorrectos!!'
				);
			}
		}
		else {
			$data = array(
				"status" => "error",
				"code" => 400,
				"msg" => "Autorización no válida"
			);

		}
		
	
		return $helpers->json($data);
	}


	public function listaRestauranteAction(Request $request) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();

			$dql = "SELECT e FROM BackendBundle:Encargo e WHERE e.restaurante = {$identity->id} ORDER BY e.id DESC";
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

	public function listaUsuarioAction(Request $request) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();

			$dql = "SELECT e FROM BackendBundle:Encargo e WHERE e.usuario = {$identity->id} AND e.createat > CURRENT_TIMESTAMP()-3600*24 ORDER BY e.id DESC";
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

	public function informacionRestauranteAction(Request $request, $id = null) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();
			$encargo = $em->getRepository('BackendBundle:Encargo')->findOneBy(array(
				'id' => $id
			));

			if($encargo && is_object($encargo) && $identity->id == $encargo->getRestaurante()->getId()) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $encargo
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Encargo no encontrado'
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

	public function informacionComandaNuevaAction(Request $request) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);
		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();
			
			$dql = "SELECT e FROM BackendBundle:Encargo e JOIN e.usuario u WHERE u.id = {$identity->id} AND e.estado = 'Nou'";

			$query = $em->createQuery($dql);
			$encargo = $query->getResult();

			$data = array(
				'status' => 'success',
				'code' => 200,
				'data' => $encargo[0]
			);
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

	public function informacionComandaRealizadaAction(Request $request) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);
		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();
			
			$dql = "SELECT e FROM BackendBundle:Encargo e JOIN e.usuario u WHERE u.id = {$identity->id} AND e.estado != 'Nou' ORDER BY e.createat DESC";

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
				'msg' => 'Autorización no válida'
			);
		}

		return $helpers->json($data);
	}

	public function informacionEncargosRestauranteAction(Request $request) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);
		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();
			
			$dql = "SELECT e FROM BackendBundle:Encargo e JOIN e.restaurante r WHERE r.id = {$identity->id} AND e.estado != 'Nou' ORDER BY e.createat DESC";

			$query = $em->createQuery($dql);

			$pagina = $request->query->getInt('pagina', 1);
			$paginador = $this->get('knp_paginator');
			$items_por_pagina = 20;

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
				'msg' => 'Autorización no válida'
			);
		}

		return $helpers->json($data);
	}

	public function informacionUsuarioAction(Request $request, $id = null) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();
			$encargo = $em->getRepository('BackendBundle:Encargo')->findOneBy(array(
				'id' => $id
			));

			if($encargo && is_object($encargo) && $identity->id == $encargo->getUsuario()->getId()) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $encargo
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Encargo no encontrado'
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

	public function eliminarAction(Request $request, $id = null) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();
			$encargo = $em->getRepository('BackendBundle:Encargo')->findOneBy(array(
				'id' => $id
			));

			if($encargo && is_object($encargo) && $identity->id == $encargo->getRestaurante()->getId()) {
				// Borrar objeto y borrar registro de la tabla bbdd
				$em->remove($encargo);
				$em->flush();

				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $encargo
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Encargo no encontrado'
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