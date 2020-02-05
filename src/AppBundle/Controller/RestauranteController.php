<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Restaurante;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;


class RestauranteController extends Controller {

	public function pruebaAction() {
		echo "Hola Mundo Controlador Restaurante";
		die();
	}

	public function nuevoAction(Request $request) {
		$helpers = $this->get(Helpers::class);

		$json = $request->get("json", null);
		$params = json_decode($json);

		$data = array(
			'status' => 'error',
			'code' => 400,
			'msg' => 'Restaurante no creado!!'
		);

		if ($json != null) {

			$nombre = (isset($params->nombre)) ? $params->nombre : null;
			$contrasena = (isset($params->contrasena)) ? $params->contrasena : null;
			$telefono = (isset($params->telefono)) ? $params->telefono : null;
			$email = (isset($params->email)) ? $params->email : null;
			$direccionRestaurante = (isset($params->direccionRestaurante)) ? $params->direccionRestaurante : null;

			$emailConstraint = new Assert\Email();
			$emailConstraint->message = "El email no es valido !!";
			$valid_email = $this->get("validator")->validate($email, $emailConstraint);
			

			if ($email != null && count($valid_email) == 0 && $contrasena != null
				&& $nombre != null && $telefono != null && $direccionRestaurante != null) {
				
				$em = $this->getDoctrine()->getManager();

				$restaurante = new Restaurante();
				$restaurante->setEmail($email);
				$restaurante->setNombre($nombre);
				$restaurante->setTelefono($telefono);
				$restaurante->setDireccionRestaurante($direccionRestaurante);
				$restaurante->setTiempopedidos(0);

				// Cifrar Password
				$pwd = hash('sha256', $contrasena);
				$restaurante->setContrasena($pwd);

	
				$isset_nombre_restaurante = $em->getRepository('BackendBundle:Restaurante')->findBy(
					array("nombre" => $nombre));

				$isset_email_restaurante = $em->getRepository('BackendBundle:Restaurante')->findBy(
					array("email" => $email));

				if(count($isset_nombre_restaurante) == 0 && count($isset_email_restaurante) == 0) {
					$em->persist($restaurante);
					$em->flush();
				
					$data = array(
						'status' => 'success',
						'code' => 200,
						'msg' => 'Nuevo Restaurante creado!!',
						'restaurante' => $restaurante
					);
				}
				else {
					$data = array(
						'status' => 'error',
						'code' => 400,
						'msg' => 'Restaurante no creado, ya existe!!'
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
			$restaurante = $em->getRepository('BackendBundle:Restaurante')->findOneBy(array(
				'id' => $identity->id
			));

			// Recoger datos post
			$json = $request->get("json", null);
			$params = json_decode($json);

			// Array de error por defecto
			$data = array(
				'status' => 'error',
				'code' => 400,
				'msg' => 'Restaurante no editado!!'
			);

			if ($json != null) {

				$nombre = (isset($params->nombre)) ? $params->nombre : null;
				$contrasena = (isset($params->contrasena)) ? $params->contrasena : null;
				$telefono = (isset($params->telefono)) ? $params->telefono : null;
				$email = (isset($params->email)) ? $params->email : null;
				$direccionRestaurante = (isset($params->direccionRestaurante)) ? $params->direccionRestaurante : null;

				$emailConstraint = new Assert\Email();
				$emailConstraint->message = "El email no es valido !!";
				$valid_email = $this->get("validator")->validate($email, $emailConstraint);

				if ($email != null && count($valid_email) == 0
					&& $nombre != null && $telefono != null && $direccionRestaurante != null) {

					$restaurante->setEmail($email);
					$restaurante->setNombre($nombre);
					$restaurante->setTelefono($telefono);
					$restaurante->setDireccionRestaurante($direccionRestaurante);
					
					// Cifrar Password
					if($contrasena != null && $contrasena != '') {
						$pwd = hash('sha256', $contrasena);
						$restaurante->setContrasena($pwd);
					}

					$isset_nombre_restaurante = $em->getRepository('BackendBundle:Restaurante')->findBy(
					array("nombre" => $nombre));

					$isset_email_restaurante = $em->getRepository('BackendBundle:Restaurante')->findBy(
					array("email" => $email));

					if((count($isset_nombre_restaurante) == 0 || $identity->nombre == $nombre)
						&& (count($isset_email_restaurante) == 0 || $identity->email == $email)) {
						$em->persist($restaurante);
						$em->flush();
					
						$data = array(
							'status' => 'success',
							'code' => 200,
							'msg' => 'Nuevo Restaurante editado!!',
							'restaurante' => $restaurante
						);
					}
					else {
						$data = array(
							'status' => 'error',
							'code' => 400,
							'msg' => 'Restaurante no editado, ya existe!!'
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

	public function editarTiempoEsperaAction(Request $request) {
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
			$restaurante = $em->getRepository('BackendBundle:Restaurante')->findOneBy(array(
				'id' => $identity->id
			));

			// Recoger datos post
			$json = $request->get("json", null);
			$params = json_decode($json);

			// Array de error por defecto
			$data = array(
				'status' => 'error',
				'code' => 400,
				'msg' => 'Restaurante no editado!!'
			);

			if ($json != null && $restaurante!= null) {
				$tiempoPedidos = (isset($params->tiempoPedidos)) ? $params->tiempoPedidos : null;
				if ($tiempoPedidos != null) {
					$restaurante->setTiempopedidos($tiempoPedidos);
				
					$em->persist($restaurante);
					$em->flush();
					
					$data = array(
						'status' => 'success',
						'code' => 200,
						'msg' => 'Nuevo Restaurante editado!!',
						'restaurante' => $restaurante
					);
				}
				else {
					$data = array(
						'status' => 'error',
						'code' => 400,
						'msg' => 'Tiempo pedido no modificado!!'
					);
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

	public function informacionAction($nombre = null) {
		$helpers = $this->get(Helpers::class);
		
		if($nombre != null) {
			$em = $this->getDoctrine()->getManager();

			$restaurante = $em->getRepository('BackendBundle:Restaurante')->findBy(
				array("nombre" => $nombre));
		
			if($restaurante != null) {
				$data = array(
					'status' => 'success',
					'code' => 200,
					'msg' => 'Restaurante encontrado!!',
					'restaurante' => $restaurante[0]
				);
			}

			else {
				$data = array(
					'status' => 'error',
					'code' => 400,
					'msg' => 'Restaurante no encontrado!!'
				);
			}
		}

		else {
			$data = array(
				'status' => 'error',
				'code' => 400,
				'msg' => 'Nombre no encontrado!!'
			);
		}
		
		return $helpers->json($data);
	}

}