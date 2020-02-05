<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Restaurante;
use BackendBundle\Entity\Categoria;
use BackendBundle\Entity\Plato;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class CategoriaController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Categoria";
		die();
	}

	public function nuevaAction(Request $request, $id = null) {
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
				$restaurante_id = ($identity->id != null) ? $identity->id : null;

				$em = $this->getDoctrine()->getManager();
				$restaurante = $em->getRepository("BackendBundle:Restaurante")->findOneBy(array(
							"id" => $identity->id,
							"email" => $identity->email
						));

				if ($restaurante != null) {

					if($id == null) {
						$categoria = new Categoria();
						$categoria->setNombre($nombre);
						$categoria->setImagen($imagen);
						$categoria->setRestaurante($restaurante);
			
						$em->persist($categoria);
						$em->flush();
					
						$data = array(
							'status' => 'success',
							'code' => 200,
							'msg' => 'Nueva Categoría creada!!',
							'categoria' => $categoria
						);
					}
					else {
						$categoria = $em->getRepository("BackendBundle:Categoria")->findOneBy(array(
							"id" => $id
						));

						if (isset($identity->id) && $identity->id == $categoria->getRestaurante()->getId()) {

							$categoria->setNombre($nombre);
							$categoria->setImagen($imagen);
							$categoria->setRestaurante($restaurante);

							$em->persist($categoria);
							$em->flush();

							$data = array(
								'status' => 'success',
								'code' => 200,
								'msg' => 'Modificación Categoria echa!!',
								'categoria' => $categoria
							);

						}
						else {
							$data = array(
								'status' => 'error',
								'code' => 400,
								'msg' => 'Error en la modificación Categoria, no eres el propietario!!'
							);
						}
					}
					
				}
				else {
					$data = array(
						"status" => "error",
						"code" => 400,
						"msg" => "Categoría no creada, validación incorrecta!!"
					);
				}
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Categoría no creada!!'
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

		$em = $this->getDoctrine()->getManager();

		if ($token == null) {
			$dql = "SELECT c FROM BackendBundle:Categoria c JOIN c.restaurante r WHERE r.nombre = 'Ingredients' ORDER BY c.id DESC";
		}
		else {
			$authCheck = $jwt_auth->checkToken($token);
			$identity = $jwt_auth->checkToken($token, true);
			$dql = "SELECT c FROM BackendBundle:Categoria c WHERE c.restaurante = {$identity->id} ORDER BY c.id DESC";
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
		
		return $helpers->json($data);
	}


	public function informacionAction(Request $request, $id = null) {

		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);

		$em = $this->getDoctrine()->getManager();
		

		if ($token == null) {
			$restaurante = $em->getRepository('BackendBundle:Restaurante')->findOneBy(array(
				'nombre' => 'Ingredients'
			));
			
			$categoria = $em->getRepository('BackendBundle:Categoria')->findOneBy(array(
				'restaurante' => $restaurante,
				'id' => $id
			));

			if ($categoria) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $categoria
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Categoria no encontrado'
				);
			}
			
		}
		else {
			$authCheck = $jwt_auth->checkToken($token);
			$identity = $jwt_auth->checkToken($token, true);

			$categoria = $em->getRepository('BackendBundle:Categoria')->findOneBy(array(
				'id' => $id
			));
			
			if($categoria && is_object($categoria) && $identity->id == $categoria->getRestaurante()->getId()) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $categoria
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Categoria no encontrado'
				);
			}
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
			$categoria = $em->getRepository('BackendBundle:Categoria')->findOneBy(array(
				'id' => $id
			));

			if($categoria && is_object($categoria) && $identity->id == $categoria->getRestaurante()->getId()) {
				// Borrar objeto y borrar registro de la tabla bbdd

				$platos = $em->getRepository('BackendBundle:Plato')->findBy(array(
					'categoria' => $categoria
				));

				$combos = $em->getRepository('BackendBundle:Combo')->findBy(array(
					'categoria' => $categoria
				));

				$data = array(
					"status" => "success",
					"code" => 200,
					"categoria" => $categoria,
					"platos" => $platos,
					"combos" => $combos
				);


				for ($i = 0; $i < sizeof($platos); ++$i) {
					$em->remove($platos[$i]);
				}
				for ($i = 0; $i < sizeof($combos); ++$i) {
					$em->remove($combos[$i]);
				}
				
				$em->remove($categoria);
				$em->flush();
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Categoria no encontrado'
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

	public function subirImagenAction(Request $request, $id = null) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization', null);
		$authCheck = $jwt_auth->checkToken($token);



		if($authCheck) {
			$identity = $jwt_auth->checkToken($token, true);

			$categoria_id = $id;

			$em = $this->getDoctrine()->getManager();
			$categoria = $em->getRepository("BackendBundle:Categoria")->findOneBy(array(
				"id" => $categoria_id
			));



			if($categoria_id != null && isset($identity->id) && $identity->id == $categoria->getRestaurante()->getId()) {
				$file = $request->files->get('imagen', null);
				if($file != null && !empty($file)) {
					$ext = $file->guessExtension();
					if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
						$file_name = time().".".$ext;
						$file->move("uploads/categoria/imagenes", $file_name);
						$categoria->setImagen($file_name);

						$em->persist($categoria);
						$em->flush();

						$data = array(
							"status" => "success",
							"code" => 200,
							"categoria" => $categoria
						);
					}
					else {
						$data = array(
							'status' => 'error',
							'code' => 400,
							'msg' => 'La extensión de la imagen no es correcta'
						);
					}
				}
				else {
					$data = array(
						'status' => 'error',
						'code' => 400,
						'msg' => 'No se ha seleccionado ninguna imagen'
					);
				}
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'No se ha encontrado la categoria'
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