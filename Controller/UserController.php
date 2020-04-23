<?php

namespace ProjetNormandie\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function autocomplete( Request $request)
    {
        $q = $request->query->get('query', null);

        return $this->getDoctrine()->getRepository('ProjetNormandieUserBundle:User')->autocomplete($q);
    }
}
