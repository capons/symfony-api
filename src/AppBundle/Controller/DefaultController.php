<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;


/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        if($request->isMethod('POST')){
            return new response('ok');
        } else {



            $body = '<h1 style="text-align:center;">Only http request!!!</h1>';
            return Response::create($body, 403)
                ->setSharedMaxAge(300);
        }
    }

}