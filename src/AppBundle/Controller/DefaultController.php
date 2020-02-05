<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    public function loginUsuarioAction(Request $request) {
        $helpers = $this->get(Helpers::class);

        // Recibir JSON POST
        $json = $request->get('json', null);
        
        // Array a devolver por defecto
        $data = array(
            'status'    => 'error',
            'data'      => 'Enviar json via post!!'
        );

        if ($json != null) {
            // me hace el login

            // Convertimos objeto php
            $params = json_decode($json);

            $email = (isset($params->email)) ? $params->email : null;
            $contrasena = (isset($params->contrasena)) ? $params->contrasena : null;
            $getHash = (isset($params->getHash)) ? $params->getHash : null;

            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "Este email no es valido !!";
            $validate_email = $this->get("validator")->validate($email, $emailConstraint);

            // Cifrar contraseña
            $pwd = hash('sha256', $contrasena);

            if($email != null && $contrasena != null && count($validate_email) == 0) {

                $jwt_auth = $this->get(JwtAuth::class);

                if ($getHash == null || $getHash == false) 
                    $signup = $jwt_auth->signupUsuario($email,$pwd);
                else
                    $signup = $jwt_auth->signupUsuario($email, $pwd, true);

                $data = array(
                    'status'    => 'success',
                    'data'      => 'Login correcto',
                    'signup'    => $signup
                );
                return $this->json($signup);
            } 
            else {
                $data = array(
                    'status'    => 'error',
                    'data'      => 'Email o contraseña incorrectos'
                );
            }


        }

        return $helpers->json($data);
    }

    public function loginRestauranteAction(Request $request) {
        $helpers = $this->get(Helpers::class);

        // Recibir JSON POST
        $json = $request->get('json', null);
        
        // Array a devolver por defecto
        $data = array(
            'status'    => 'error',
            'data'      => 'Enviar json via post!!'
        );

        if ($json != null) {
            // me hace el login

            // Convertimos objeto php
            $params = json_decode($json);

            $email = (isset($params->email)) ? $params->email : null;
            $contrasena = (isset($params->contrasena)) ? $params->contrasena : null;
            $getHash = (isset($params->getHash)) ? $params->getHash : null;
            
            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "Este email no es valido !!";
            $validate_email = $this->get("validator")->validate($email, $emailConstraint);

            // Cifrar contraseña
            $pwd = hash('sha256', $contrasena);

            if($email != null && $contrasena != null && count($validate_email) == 0) {

                $jwt_auth = $this->get(JwtAuth::class);

                if ($getHash == null || $getHash == false) 
                    $signup = $jwt_auth->signupRestaurante($email,$pwd);
                else
                    $signup = $jwt_auth->signupRestaurante($email, $pwd, true);

                $data = array(
                    'status'    => 'success',
                    'data'      => 'Login correcto',
                    'signup'    => $signup
                );
                return $this->json($signup);
            } 
            else {
                $data = array(
                    'status'    => 'error',
                    'data'      => 'Email o contraseña incorrectos'
                );
            }


        }

        return $helpers->json($data);
    }

    public function pruebasAction(Request $request) {
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);
        $token = $request->get("authorization", null);
        
        if($token && $jwt_auth->checkToken($token)) {
            $em = $this->getDoctrine()->getManager();
            $restauranteRepo = $em->getRepository('BackendBundle:Restaurante');
            $restaurante = $restauranteRepo->findAll();
 
            return $helpers->json($restaurante);
            die();

            return new JsonResponse(array(
                'status'        => 'success',
                'restaurante'   => $restaurante[0]->getNombre()
            ));
        }
        else {
            return $helpers->json(array(
                'status'        => 'error',
                'data'          => "Authorization no valida"
            ));
        }
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
