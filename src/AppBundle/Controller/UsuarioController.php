<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Usuario;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class UsuarioController extends Controller {

	public function pruebaAction() {
		$em = $this->getDoctrine()->getManager();
		$userRepo = $em->getRepository('BackendBundle:Usuario');
		$users = $userRepo->findAll();

		var_dump($users);

		die();
	}

	public function nuevoAction(Request $request) {
		$helpers = $this->get(Helpers::class);


		$json = $request->get("json", null);
		$params = json_decode($json);

		$data = array(
			'status' => 'error',
			'code' => 400,
			'msg' => 'Usuario no creado!!',
			'json' => ($json != null)
		);

		if ($json != null) {
			$createdAt = new \Datetime("now");

			$nombre = (isset($params->nombre)) ? $params->nombre : null;
			$apellidos = (isset($params->apellidos)) ? $params->apellidos : null;
			$contrasena = (isset($params->contrasena)) ? $params->contrasena : null;
			$telefono = (isset($params->telefono)) ? $params->telefono : null;
			$email = (isset($params->email)) ? $params->email : null;
			$direccionCasa = (isset($params->direccionCasa)) ? $params->direccionCasa : null;

			$emailConstraint = new Assert\Email();
			$emailConstraint->message = "El email no es valido !!";
			$valid_email = $this->get("validator")->validate($email, $emailConstraint);


			if ($email != null && count($valid_email) == 0 && $contrasena != null
				&& $nombre != null && $apellidos != null) {

				$em = $this->getDoctrine()->getManager();

				$usuario = new Usuario();
				$usuario->setCreateat($createdAt);
				$usuario->setEmail($email);
				$usuario->setNombre($nombre);
				$usuario->setApellidos($apellidos);
				$usuario->setTelefono($telefono);
				$usuario->setDireccionCasa($direccionCasa);

					
				// Cifrar Password
				$pwd = hash('sha256', $contrasena);
				$usuario->setContrasena($pwd);

	
				$isset_ususario = $em->getRepository('BackendBundle:Usuario')->findBy(
					array("email" => $email));
				if(count($isset_ususario) == 0) {
					$em->persist($usuario);
					$em->flush();
				
					$data = array(
						'status' => 'success',
						'code' => 200,
						'msg' => 'Nuevo Usuario creado!!',
						'usuario' => $usuario
					);
				}
				else {
					$data = array(
						'status' => 'error',
						'code' => 400,
						'msg' => 'Usuario no creado, ya existe!!'
					);
				}
			}
		}

		return $helpers->json($data);
	}

	public function editarAction(Request $request) {
		$helpers = $this->get(Helpers::class);
		$jwt_auth = $this->get(JwtAuth::class);

		$token = $request->get('authorization',null);
		$authCheck = $jwt_auth->checkToken($token);
		if ($authCheck) {

			// Entity manager
			$em = $this->getDoctrine()->getManager();

			// Conseguir los datos del usuario identificado via token
			$identity = $jwt_auth->checkToken($token, true);

			// Conseguir el objeto a actualizar
			$usuario = $em->getRepository('BackendBundle:Usuario')->findOneBy(array(
				'id' => $identity->id
			));

			// Recoger datos post
			$json = $request->get("json", null);
			$params = json_decode($json);

			// Array de error por defecto
			$data = array(
				'status' => 'error',
				'code' => 400,
				'msg' => 'Usuario no editado!!'
			);

			if ($json != null) { 
				$nombre = (isset($params->nombre)) ? $params->nombre : null;
				$apellidos = (isset($params->apellidos)) ? $params->apellidos : null;
				$contrasena = (isset($params->contrasena)) ? $params->contrasena : null;
				$telefono = (isset($params->telefono)) ? $params->telefono : null;
				$email = (isset($params->email)) ? $params->email : null;
				$direccionCasa = (isset($params->direccionCasa)) ? $params->direccionCasa : null;

				$emailConstraint = new Assert\Email();
				$emailConstraint->message = "El email no es valido !!";
				$valid_email = $this->get("validator")->validate($email, $emailConstraint);

				if ($email != null && count($valid_email) == 0
				&& $nombre != null && $apellidos != null) {

					$usuario->setEmail($email);
					$usuario->setNombre($nombre);
					$usuario->setApellidos($apellidos);
					$usuario->setTelefono($telefono);
					$usuario->setDireccionCasa($direccionCasa);

					// Cifrar Password
					if($contrasena != null) {
						$pwd = hash('sha256', $contrasena);
						$usuario->setContrasena($pwd);
					}
		
					$isset_usuario = $em->getRepository('BackendBundle:Usuario')->findBy(
						array("email" => $email));

					if(count($isset_usuario) == 0 || $identity->email == $email) {
						$em->persist($usuario);
						$em->flush();
					
						$data = array(
							'status' => 'success',
							'code' => 200,
							'msg' => 'Nuevo Usuario editado!!',
							'usuario' => $usuario
						);
					}
					else {
						$data = array(
							'status' => 'error',
							'code' => 400,
							'msg' => 'Usuario no editado, ya existe!!'
						);
					}
				}
			}
		}
		else {
			$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Autorización no válida!!'
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

			$dql = "SELECT u FROM BackendBundle:Usuario u ORDER BY u.id DESC";
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
			$usuario = $em->getRepository('BackendBundle:Usuario')->findOneBy(array(
				'id' => $id
			));

			if($usuario && is_object($usuario)) {
				$data = array(
					"status" => "success",
					"code" => 200,
					"msg" => $usuario
				);
			}
			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Usuario no encontrado'
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