<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\FOSRestBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


use AppBundle\Entity\User;
use AppBundle\Entity\Group;
use AppBundle\Entity\Address;
use AppBundle\Entity\Image;
use AppBundle\Entity\Country;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;




class RestController extends FOSRestController
{
    //display all user
    /**
     * @Rest\Get("/user")
     * */
    public function getTestAction(Request $request)
    {

        $response = array();
        header("Access-Control-Allow-origin: *");
        header("Content-Type: application/json");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User');
        $all_user = $user->findAll();
        $serializer = $this->get('jms_serializer');
       // $serializer = $serializer::create()->build();
    //    $array = $serializer->toArray($all_user);

        $response['body'] = $all_user;//$array;

        return new Response($serializer->serialize($response,'json'));

    }


    /**
     * @Rest\Get("/user/{id}")
     */
    public function idAction($id)
    {
        //display only one user
        $serializer = $this->get('jms_serializer');
        return new Response($serializer->serialize($id,'json'));
    }




    /**
     * @Rest\Post("/user/")
     */
    //save new user
    public function postAction($slug,Request $request)
    {


        header("Access-Control-Allow-origin: *");
        header("Content-Type: application/json");
        $form_data = $request->request->all();

        //save form data
        //$request->query->get('id'); retrive post get data example
        $user = new User();
        $address = new Address();
        $user_permission = new Group();
        $image = new Image();

        //response to API
        $response = array();
        $serializer = $this->get('jms_serializer');

        //NEED VALIDATE FORM DATA IN FUTURE



            $repository = $this->getDoctrine()->getRepository('AppBundle:User');

            $check_duplicat_name = $repository->findOneByUsername($form_data['data']['name']);

            //check duplicat
            if($check_duplicat_name){

                $response['error'] = 'Username already exist!';
                $array = $serializer->toArray($response);
                return new JsonResponse($array);


            }
            $check_duplicat_email = $repository->findOneByEmail($form_data['data']['email']);
            //check duplicat
            if($check_duplicat_email){

                $response['error'] = 'Email already exist!';
                $array = $serializer->toArray($response);
                return new JsonResponse($array);
            }


            $em = $this->getDoctrine()->getManager();
            $user_role = $em->getRepository('AppBundle:Role')
                ->loadRoleByRolename('ROLE_USER'); //my custom repository

            $user_country = $em->getRepository('AppBundle:Country')
                ->loadCountryByName($form_data['data']['country']);
            //*/

            //save form data to database
            $image->setPath($form_data['data']['file_path']);
            $address->setAddress($form_data['data']['address']);
            $pwd=$user->getPassword();
            $encoder=$this->container->get('security.password_encoder');
            $pwd=$encoder->encodePassword($user, $pwd);
            $user->setPassword($pwd);
            $user->setUsername($form_data['data']['name']);
            $user->setEmail($form_data['data']['email']);
            //set user relation
            $user->setAddress($address);
            $user->setCountry($user_country);
            $user->setImage($image);
            //add user permission
            $user_permission->setName($form_data['data']['name']);
            $user_permission->setUserRole($user_role);

            $user->addGroup($user_permission);
            $em = $this->getDoctrine()->getManager();

            $em->persist($address);
            $em->persist($user);
            $em->persist($user_permission);
            $em->persist($image);
            $em->flush();

        //return last save object
        $last_object = $repository->find($user->getId());

        // $serializer = $serializer::create()->build();
        $array = $serializer->toArray($last_object);
        $response['body'] = $array;

        return new JsonResponse($response);
    }


    /**
     * Rest\Delete("/user/{id}")
     * @Route("/user/{id}")
     * @Method({"GET","DELETE"})
     */
    public function deleteUserAction($id)
    {

        $result = array();
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:User')->find($id);
        if (!$product) {
            $result['code'] = 201;
        }

        $em->remove($product);
        $em->flush();




        return new JsonResponse();


        /*
        $data = new User;
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $sn->remove($user);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
        */
    }





}