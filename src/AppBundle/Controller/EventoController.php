<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Evento;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;


class EventoController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Evento";
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
				$imagen = (isset($params->imagen)) ? $params->imagen : null;
				$descripcion = (isset($params->descripcion)) ? $params->descripcion : null;

				$em = $this->getDoctrine()->getManager();
				$restaurante = $em->getRepository("BackendBundle:Restaurante")->findOneBy(array(
							"id" => $identity->id,
							"email" => $identity->email
						));

				if ($restaurante != null) {

					if ($id == null) {
						$evento = new Evento();
						$evento->setNombre($nombre);
						$evento->setImagen($imagen);
						$evento->setDescripcion($descripcion);
						$evento->setRestaurante($restaurante);
					
						$isset_categoria = $em->getRepository('BackendBundle:Evento')->findBy(
						array("nombre" => $nombre));
						
						$em->persist($evento);
						$em->flush();
					
						$data = array(
							'status' => 'success',
							'code' => 200,
							'msg' => 'Nuevo Evento creado!!',
							'evento' => $evento
						);
					}
					else {
						$evento = $em->getRepository("BackendBundle:Evento")->findOneBy(array(
							"id" => $id
						));

						if (isset($identity->id) && $identity->id == $evento->getRestaurante()->getId()) {

							$evento->setNombre($nombre);
							$evento->setImagen($imagen);
							$evento->setDescripcion($descripcion);

							$em->persist($evento);
							$em->flush();

							$data = array(
								'status' => 'success',
								'code' => 200,
								'msg' => 'Modificación Evento echa!!',
								'evento' => $evento
							);

						}
						else {
							$data = array(
								'status' => 'error',
								'code' => 400,
								'msg' => 'Error en la modificación Evento, no eres el propietario!!'
							);
						}
					}
				}
				else {
					$data = array(
						"status" => "error",
						"code" => 400,
						"msg" => "Evento no creado, validación incorrecta!!"
					);
				}
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Evento no creado, parámetros incorrectos!!'
				);
			}
		}
		else {
			$data = array(
				"status" => "error",
				"code" => 400,
				"msg" => "Autorización no valida"
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

			$dql = "SELECT e FROM BackendBundle:Evento e WHERE e.restaurante = {$identity->id} ORDER BY e.id DESC";
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
			$evento = $em->getRepository('BackendBundle:Evento')->findOneBy(array(
				'id' => $id
			));

			if($evento && is_object($evento) && $identity->id == $evento->getRestaurante()->getId()) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $evento
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Evento no encontrado'
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
			$evento = $em->getRepository('BackendBundle:Evento')->findOneBy(array(
				'id' => $id
			));

			if($evento && is_object($evento) && $identity->id == $evento->getRestaurante()->getId()) {
				// Borrar objeto y borrar registro de la tabla bbdd
				$em->remove($evento);
				$em->flush();

				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $evento
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Evento no encontrado'
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