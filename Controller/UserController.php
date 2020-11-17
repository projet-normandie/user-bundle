<?php

namespace ProjetNormandie\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function autocomplete(Request $request)
    {
        $q = $request->query->get('query', null);

        return $this->getDoctrine()->getRepository('ProjetNormandieUserBundle:User')->autocomplete($q);
    }
}
