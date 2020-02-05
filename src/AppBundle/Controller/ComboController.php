<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Combo;
use BackendBundle\Entity\Categoria;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;


class ComboController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Combo";
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
				$nombreCategoria = (isset($params->nombreCategoria)) ? $params->nombreCategoria : null;
				$descripcion = (isset($params->descripcion)) ? $params->descripcion : null;
				$imagen = (isset($params->imagen)) ? $params->imagen : null;
				$precio = (isset($params->precio)) ? $params->precio : null;
				
				$em = $this->getDoctrine()->getManager();
				$restaurante = $em->getRepository("BackendBundle:Restaurante")->findOneBy(array(
							"id" => $identity->id,
							"email" => $identity->email
						));
				$categoria = $em->getRepository("BackendBundle:Categoria")->findOneBy(array(
							"nombre" => $nombreCategoria
						));

				if ($categoria != null && $restaurante != null) {

					if ($id == null) {
						$combo = new Combo();
						$combo->setNombre($nombre);
						$combo->setCategoria($categoria);
						$combo->setDescripcion($descripcion);
						$combo->setPrecio($precio);
						$combo->setImagen($imagen);
			
						$em->persist($combo);
						$em->flush();
					
						$data = array(
							'status' => 'success',
							'code' => 200,
							'msg' => 'Nuevo Evento creado!!',
							'combo' => $combo
						);
					}
					else {
						$combo = $em->getRepository("BackendBundle:Combo")->findOneBy(array(
							"id" => $id
						));

						if ($combo != null && isset($identity->id) && $identity->id == $combo->getCategoria()->getRestaurante()->getId()) {

							$combo->setNombre($nombre);
							$combo->setCategoria($categoria);
							$combo->setDescripcion($descripcion);
							$combo->setPrecio($precio);
							$combo->setImagen($imagen);

							$em->persist($combo);
							$em->flush();

							$data = array(
								'status' => 'success',
								'code' => 200,
								'msg' => 'Modificación Combo echa!!',
								'evento' => $combo
							);

						}
						else {
							$data = array(
								'status' => 'error',
								'code' => 400,
								'msg' => 'Error en la modificación Combo, no eres el propietario!!'
							);
						}
					}	
				}
				else {
					$data = array(
						"status" => "error",
						"code" => 400,
						"msg" => "Combo no creado, validación incorrecta!!"
					);
				}
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Combo no creado, parámetros incorrectos!!'
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

			$dql = "SELECT co FROM BackendBundle:Combo co JOIN co.categoria ca WHERE ca.restaurante = {$identity->id} ORDER BY co.id DESC";
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
			$combo = $em->getRepository('BackendBundle:Combo')->findOneBy(array(
				'id' => $id
			));

			if($combo && is_object($combo) && $identity->id == $combo->getCategoria()->getRestaurante()->getId()) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $combo
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Combo no encontrado'
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
			$combo = $em->getRepository('BackendBundle:Combo')->findOneBy(array(
				'id' => $id
			));

			if($combo && is_object($combo) && $identity->id == $combo->getCategoria()->getRestaurante()->getId()) {
				// Borrar objeto y borrar registro de la tabla bbdd
				$em->remove($combo);
				$em->flush();

				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $combo
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Combo no encontrado'
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