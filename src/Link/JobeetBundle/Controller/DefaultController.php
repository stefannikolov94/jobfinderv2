<?php

namespace Link\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LinkJobeetBundle:Default:index.html.twig');
    }

    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('LinkJobeetBundle:Default:login.html.twig', array(
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ));
    }
}
