<?php

namespace App\Controller\Inventario\Informe\Compra\Solicitud;

use App\Controller\MaestroController;
use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\Inventario\InvSolicitudTipo;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class pendienteController extends MaestroController
{


    public $tipo = "proceso";
    public $proceso = "invi0016";


    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/compra/solicitud/pendientes", name="inventario_informe_inventario_compra_solicitud_pendiente")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('solicitudTipoRel',EntityType::class,$em->getRepository(InvSolicitudTipo::class)->llenarCombo())
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel','attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $arSolicitudTipo = $form->get('solicitudTipoRel')->getData();
                if($arSolicitudTipo){
                    /** @var  $arSolicitudTipo InvSolicitudTipo */
                    $arSolicitudTipo = $arSolicitudTipo->getCodigoSolicitudTipoPk();
                }
                $session->set('filtroInvCodigoSolicitudTipo', $arSolicitudTipo);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvSolicitudDetalle::class)->pendientes()->getQuery()->getResult(), "Informe solicitudes pendientes");
            }
        }
        $arSolicitudDetalles = $paginator->paginate($em->getRepository(InvSolicitudDetalle::class)->pendientes(), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/informe/inventario/solicitud/solicitudesPendientes.html.twig', [
            'arSolicitudDetalles' => $arSolicitudDetalles,
            'form' => $form->createView()
        ]);
    }
}

