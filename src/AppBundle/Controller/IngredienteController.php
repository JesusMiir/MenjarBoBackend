<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Ingrediente;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;


class IngredienteController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Ingrediente";
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

			if ($json != null) {
				$params = json_decode($json);

				$nombre = (isset($params->nombre)) ? $params->nombre : null;
				$descripcion = (isset($params->descripcion)) ? $params->descripcion : null;
				$precio = (isset($params->precio)) ? $params->precio : null;
				
				$em = $this->getDoctrine()->getManager();
				$restaurante = $em->getRepository("BackendBundle:Restaurante")->findOneBy(array(
							"id" => $identity->id,
							"email" => $identity->email
						));

				if ($restaurante != null) {
					
					if ($id == null) {
						$ingrediente = new Ingrediente();
						$ingrediente->setRestaurante($restaurante);
						$ingrediente->setNombre($nombre);
						$ingrediente->setDescripcion($descripcion);
						$ingrediente->setPrecio($precio);
			
						$em->persist($ingrediente);
						$em->flush();
					
						$data = array(
							'status' => 'success',
							'code' => 200,
							'msg' => 'Nuevo Ingrediente creado!!',
							'ingrediente' => $ingrediente
						);
					}
					else {
						$ingrediente = $em->getRepository("BackendBundle:Ingrediente")->findOneBy(array(
							"id" => $id
						));

						if (isset($identity->id) && $identity->id == $ingrediente->getRestaurante()->getId()) {

							$ingrediente->setNombre($nombre);
							$ingrediente->setDescripcion($descripcion);
							$ingrediente->setPrecio($precio);
				
							$em->persist($ingrediente);
							$em->flush();
						
							$data = array(
								'status' => 'success',
								'code' => 200,
								'msg' => 'Nuevo Ingrediente creado!!',
								'ingrediente' => $ingrediente
							);

						}
						else {
							$data = array(
								'status' => 'error',
								'code' => 400,
								'msg' => 'Error en la modificación Ingrediente, no eres el propietario!!'
							);
						}
					}
					
				}
				else {
					$data = array(
						"status" => "error",
						"code" => 400,
						"msg" => "Ingrediente no creado, validación incorrecta!!"
					);
				}
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Ingrediente no creado, parámetros incorrectos!!'
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

			$dql = "SELECT i FROM BackendBundle:Ingrediente i WHERE i.restaurante = {$identity->id} ORDER BY i.id DESC";
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
			$ingrediente = $em->getRepository('BackendBundle:Ingrediente')->findOneBy(array(
				'id' => $id
			));

			if($ingrediente && is_object($ingrediente) && $identity->id == $ingrediente->getRestaurante()->getId()) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $ingrediente
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Ingrediente no encontrado'
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
			$ingrediente = $em->getRepository('BackendBundle:Ingrediente')->findOneBy(array(
				'id' => $id
			));

			if($ingrediente && is_object($ingrediente) && $identity->id == $ingrediente->getRestaurante()->getId()) {
				// Borrar objeto y borrar registro de la tabla bbdd
				$em->remove($ingrediente);
				$em->flush();

				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $ingrediente
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Ingrediente no encontrado'
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