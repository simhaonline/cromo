<?php

namespace App\Controller\Inventario\Informe\Inventario\Lote;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvLote;
use App\Formato\Inventario\ExistenciaLote;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ExistenciaController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "invi0004";



    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/lote/existencia", name="inventario_informe_inventario_lote_existencia")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, array('data' => $session->get('filtroInvInformeItemCodigo'), 'required' => false))
            ->add('txtNombreItem', TextType::class, array('data' => $session->get('filtroInvInformeItemNombre'), 'required' => false , 'attr' => ['readonly' => 'readonly']))
            ->add('txtLote', TextType::class, ['required' => false, 'data' => $session->get('filtroInvLote')])
            ->add('fechaVencimiento', DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'attr' => array('class' => 'date'),
                    'label' => 'Fecha vence: ',
                    'required' => false,
                    'data' => $session->get('filtroInvInformeFechaVence')?new \DateTime($session->get('filtroInvInformeFechaVence')):null
                ])
            ->add('cboBodega', EntityType::class, $em->getRepository(InvBodega::class)->llenarCombo())
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnPdf', SubmitType::class, array('label' => 'Pdf'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvInformeItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvInformeLote', $form->get('txtLote')->getData());
                $session->set('filtroInvInformeFechaVence', $form->get('fechaVencimiento')->getData()?$form->get('fechaVencimiento')->getData()->format('Y-m-d'):null);
                $arBodega = $form->get('cboBodega')->getData();
                if($arBodega != ''){
                    $session->set('filtroInvInformeLoteBodega', $form->get('cboBodega')->getData()->getCodigoBodegaPk());
                } else {
                    $session->set('filtroInvInformeLoteBodega', null);
                }
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvLote::class)->existencia()->getQuery()->getResult(), "Existencia");
            }
            if ($form->get('btnPdf')->isClicked()) {
                $formato = new ExistenciaLote();
                $formato->Generar($em);
            }
        }
        $arLotes = $paginator->paginate($em->getRepository(InvLote::class)->existencia(), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/informe/inventario/lote/existencia.html.twig', [
            'arLotes' => $arLotes,
            'form' => $form->createView()
        ]);
    }

}

