<?php

namespace App\Controller\Estructura;

use App\Entity\General\GenEntidad;
use App\Entity\General\GenCubo;
use App\Entity\RecursoHumano\RhuEmbargo;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

final class MovimientoController extends Controller
{


    /**
     * @author Andres Acevedo Cartagena
     * @param Request $request
     * @param $entidad
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("movimiento/{modulo}/{entidad}/lista",name="movimiento_lista")
     */
    public function movimientoLista(Request $request, $modulo, $entidad)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $arMovimientos = $paginator->paginate($em->getRepository(RhuEmbargo::class)->lista(), $request->query->getInt('page', 1), 30);
        return $this->render('estructura/movimiento/lista.html.twig', [
            'arMovimientos' => $arMovimientos,
        ]);
    }

}
