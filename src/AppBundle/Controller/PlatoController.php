<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Plato;
use BackendBundle\Entity\Categoria;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class PlatoController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Plato";
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
				$precio = (isset($params->precio)) ? $params->precio : null;
				$imagen = (isset($params->imagen)) ? $params->imagen : null;
				
				$em = $this->getDoctrine()->getManager();
				$restaurante = $em->getRepository("BackendBundle:Restaurante")->findOneBy(array(
							"id" => $identity->id,
							"email" => $identity->email
						));

				$categoria = $em->getRepository("BackendBundle:Categoria")->findOneBy(array(
							"nombre" => $nombreCategoria,
							"restaurante" => $restaurante
						));

				if ($categoria != null && $restaurante != null) {
					if ($id == null) {
						$plato = new Plato();
						$plato->setNombre($nombre);
						$plato->setCategoria($categoria);
						$plato->setDescripcion($descripcion);
						$plato->setPrecio($precio);
						$plato->setImagen($imagen);
						$plato->setContadordiario(0);
						$plato->setContadorsetmanal(0);
						$plato->setContadormensual(0);
						$plato->setContadoranual(0);
						$plato->setContadorgeneral(0);

						$em->persist($plato);
						$em->flush();
					
						$data = array(
							'status' => 'success',
							'code' => 200,
							'msg' => 'Nuevo Plato creado!!',
							'plato' => $plato
						);
					}
					else {
						$plato = $em->getRepository("BackendBundle:Plato")->findOneBy(array(
							"id" => $id
						));
						if ($plato != null && isset($identity->id) && $identity->id == $plato->getCategoria()->getRestaurante()->getId()) {

							// Categoria 
							$plato->setNombre($nombre);
							$plato->setCategoria($categoria);
							$plato->setDescripcion($descripcion);
							$plato->setPrecio($precio);
							$plato->setImagen($imagen);

							$em->persist($plato);
							$em->flush();

							$data = array(
								'status' => 'success',
								'code' => 200,
								'msg' => 'Modificación Plato echa!!',
								'plato' => $plato
							);

						}
						else {
							$data = array(
								'status' => 'error',
								'code' => 400,
								'msg' => 'Error en la modificación Plato, no eres el propietario!!'
							);
						}
					}
					
				}
				else {
					$data = array(
						"status" => "error",
						"code" => 400,
						"msg" => "Plato no creado, validación incorrecta!!"
					);
				}
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => "Plato no creado, parámetros incorrectos!!"
				);
			}
		}
		else {
			$data = array(
				"status" => "error",
				"code" => 400,
				"msg" => "Autorización no válida!!"
			);
		}

		return $helpers->json($data);
	}
	
	
	public function listaAction(Request $request, $categoria = null) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);

		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$em = $this->getDoctrine()->getManager();

			if ($categoria == null) {
				$dql = "SELECT p FROM BackendBundle:Plato p JOIN p.categoria c WHERE c.restaurante = {$identity->id} ORDER BY p.id DESC";
			}
			else {
				$dql = "SELECT p FROM BackendBundle:Plato p JOIN p.categoria c WHERE c.restaurante = {$identity->id} AND c.nombre = '{$categoria}' ORDER BY p.id DESC";
			}

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

	public function listaInformacionAction(Request $request, $categoria){
		$helpers = $this->get(Helpers::class);
		$em = $this->getDoctrine()->getManager();

		$dql = "SELECT p FROM BackendBundle:Plato p JOIN p.categoria c JOIN c.restaurante r WHERE r.nombre = 'Ingredients' AND c.nombre = '{$categoria}' ORDER BY p.precio ASC";

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
			$plato = $em->getRepository('BackendBundle:Plato')->findOneBy(array(
				'id' => $id
			));

			if($plato && is_object($plato) && $identity->id == $plato->getCategoria()->getRestaurante()->getId()) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $plato
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Plato no encontrado'
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
			$plato = $em->getRepository('BackendBundle:Plato')->findOneBy(array(
				'id' => $id
			));

			if($plato && is_object($plato) && $identity->id == $plato->getCategoria()->getRestaurante()->getId()) {
				// Borrar objeto y borrar registro de la tabla bbdd
				$em->remove($plato);
				$em->flush();

				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $plato
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Plato no encontrado'
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