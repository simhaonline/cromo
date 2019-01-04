<?php

namespace App\Controller\Crm\General;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InicioController extends AbstractController
{
    /**
     * @Route("/crm/general/inicio", name="crm_general_general_inicio_ver")
     */
    public function inicio()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('base.html.twig');
    }
}
