<?php

namespace App\Controller\Transporte\Informe\Recogida;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteRecogida;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PendienteProgramarController extends MaestroController
{


    public $tipo = "proceso";
    public $proceso = "ttei0018";





    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/recogida/recogida/pendiente/programar", name="transporte_informe_recogida_recogida_pendiente_programar")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            if ($form->get('txtCodigoCliente')->getData() != '') {
                $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
            } else {
                $session->set('filtroTteCodigoCliente', null);
                $session->set('filtroTteNombreCliente', null);
            }
        }
        if ($form->get('btnExcel')->isClicked()) {
            General::get()->setExportar($em->getRepository(TteRecogida::class)->pendienteProgramar()->getQuery()->getResult(), "Pendiente programar");
        }
        $arRecogidas = $paginator->paginate($em->getRepository(TteRecogida::class)->pendienteProgramar(), $request->query->getInt('page', 1), 40);
        return $this->render('transporte/informe/recogida/recogida/pendienteProgramar.html.twig', [
            'arRecogidas' => $arRecogidas,
            'form' => $form->createView()]);
    }


}

