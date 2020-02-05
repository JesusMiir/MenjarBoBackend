<?php
namespace AppBundle\Services;

use Firebase\JWT\JWT;

class JwtAuth {

	public $manager;
	public $key;

	public function __construct($manager) {
		$this->manager = $manager;
		$this->key = 'GA_2$$_Pss_OX3';
	}

	public function signupUsuario($email, $contrasena, $getHash = null) {

		$user = $this->manager->getRepository('BackendBundle:Usuario')->findOneBy(array(
			"email" => $email,
			"contrasena" => $contrasena	
		));

		$signup = false;
		if (is_object($user)) {
			$signup = true;
		}

		if($signup) {
			// Generar Token Jwt

			$token = array(
				"id" 			=> $user->getId(),
				"nombre" 		=> $user->getNombre(),
				"apellidos" 	=> $user->getApellidos(),
				"contrasena"	=> $user->getContrasena(),
				"telefono"		=> $user->getTelefono(),
				"email"			=> $user->getEmail(),
				"direccionCasa"	=> $user->getDireccioncasa(),
				"createat"		=> $user->getCreateat(),
				"iat"			=> time(),
				"exp"			=> time()+(7*24*60*60)
			);
			$jwt = JWT::encode($token, $this->key, 'HS256');
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));

			if ($getHash == null) { 
				$data = $jwt; 
			}
			else {
				$data = $decoded;
			}
		}
		else {
			$data = array(
				'status' 	=> 'error',
				'data' 		=> 'Email o contraseña incorrectos !! '
			);
		}
		return $data;
	}

	public function signupRestaurante($email, $contrasena, $getHash = null) {

		$restaurante = $this->manager->getRepository('BackendBundle:Restaurante')->findOneBy(array(
			"email" => $email,
			"contrasena" => $contrasena
		));

		$signup = false;
		if (is_object($restaurante)) {
			$signup = true;
		}

		if($signup) {
			// Generar Token Jwt

			$token = array(
				"id" 					=> $restaurante->getId(),
				"nombre" 				=> $restaurante->getNombre(),
				"contrasena"			=> $restaurante->getContrasena(),
				"telefono"				=> $restaurante->getTelefono(),
				"email"					=> $restaurante->getEmail(),
				"direccionRestaurante"	=> $restaurante->getDireccionrestaurante(),
				"tiempopedidos"			=> $restaurante->getTiempopedidos(),
				"iat"					=> time(),
				"exp"					=> time()+(7*24*60*60)
			);

			$jwt = JWT::encode($token, $this->key, 'HS256');
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));

			if ($getHash == null) { 
				$data = $jwt; 
			}
			else {
				$data = $decoded;
			}
		}
		else {
			$data = array(
				'status' 	=> 'error',
				'data' 		=> 'No se ha encontrado el restaurante indicado !!'
			);
		}
		return $data;
	}

	public function checkToken($jwt, $getIdentity = false) {
		$auth = false;

		try {
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));
		} catch(\UnexpectedValueException $e) {
			$auth = false;
		} catch(\DomainException $e) {
			$auth = false;
		}

		if(isset($decoded) && is_object($decoded) && isset($decoded->id)) {
			$auth = true;
		} 
		else {
			$auth = false;
		} 

		if ($getIdentity == false) {
			return $auth;
		}
		else {
			return $decoded;
		}

	}
}