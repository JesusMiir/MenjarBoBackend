<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Menu;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class MenuController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Menú";
		die();
	}

	public function nuevoAction(Request $request, $id = null) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);
			$json = $request->get("json", null);
			$params = json_decode($json);

			if ($json != null) {
			
				$nombre = (isset($params->nombre)) ? $params->nombre : null;
				$nombreprimero = (isset($params->nombrePrimero)) ? $params->nombrePrimero : null;
				$descripcionprimero = (isset($params->descripcionPrimero)) ? $params->descripcionPrimero : null;
				$nombresegundo = (isset($params->nombreSegundo)) ? $params->nombreSegundo : null;
				$descripcionsegundo = (isset($params->descripcionSegundo)) ? $params->descripcionSegundo : null;
				$precio = (isset($params->precio)) ? $params->precio : null;
				$restaurante_id = ($identity->id != null) ? $identity->id : null;

				$em = $this->getDoctrine()->getManager();
				$restaurante = $em->getRepository("BackendBundle:Restaurante")->findOneBy(array(
							"id" => $identity->id,
							"email" => $identity->email
						));

				if ($restaurante != null) {

					if($id == null) {
						$menu = new Menu();
						$menu->setRestaurante($restaurante);
						$menu->setNombre($nombre);
						$menu->setNombreprimero($nombreprimero);
						$menu->setDescripcionprimero($descripcionprimero);
						$menu->setNombresegundo($nombresegundo);
						$menu->setDescripcionsegundo($descripcionsegundo);
						$menu->setPrecio($precio);
						$menu->setContadoractual(0);
						$menu->setContadorgeneral(0);

						$em->persist($menu);
						$em->flush();
					
						$data = array(
							'status' => 'success',
							'code' => 200,
							'msg' => 'Nuevo Menú creado!!',
							'menu' => $menu
						);
					}
					else {
						$menu = $em->getRepository("BackendBundle:Menu")->findOneBy(array(
							"id" => $id
						));

						if (isset($identity->id) && $identity->id == $menu->getRestaurante()->getId()) {
							
							$menu->setNombre($nombre);
							$menu->setNombreprimero($nombreprimero);
							$menu->setDescripcionprimero($descripcionprimero);
							$menu->setNombresegundo($nombresegundo);
							$menu->setDescripcionsegundo($descripcionsegundo);
							$menu->setPrecio($precio);
							$menu->setContadoractual(0);
							$menu->setContadorgeneral(0);

							$em->persist($menu);
							$em->flush();

							$data = array(
								'status' => 'success',
								'code' => 200,
								'msg' => 'Modificación Menú echa!!',
								'menu' => $menu
							);

						}
						else {
							$data = array(
								'status' => 'error',
								'code' => 400,
								'msg' => 'Error en la modificación Menú, no eres el propietario!!'
							);
						}
					}
				}
				else {
					$data = array(
						"status" => "error",
						"code" => 400,
						"msg" => "Menú no creado, validación incorrecta!!"
					);
				}
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Menú no creado, parámetros incorrectos!!'
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

			$dql = "SELECT m FROM BackendBundle:Menu m WHERE m.restaurante = {$identity->id} ORDER BY m.id DESC";
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

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();
			$menu = $em->getRepository('BackendBundle:Menu')->findOneBy(array(
				'id' => $id
			));

			if($menu && is_object($menu) && $identity->id == $menu->getRestaurante()->getId()) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $menu
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Menú no encontrado'
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
			$menu = $em->getRepository('BackendBundle:Menu')->findOneBy(array(
				'id' => $id
			));

			if($menu && is_object($menu) && $identity->id == $menu->getRestaurante()->getId()) {
				// Borrar objeto y borrar registro de la tabla bbdd
				$em->remove($menu);
				$em->flush();

				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $menu
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Menú no encontrado'
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