<?php

namespace App\Controller\Transporte\Proceso\Transporte\Guia;

use App\Controller\MaestroController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteOperacion;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class GenerarFacturaController extends MaestroController
{
    public $tipo = "proceso";
    public $proceso = "ttep0012";


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/proceso/transporte/guia/generarfactura", name="transporte_proceso_transporte_guia_generarfactura")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('txtDespachoCodigo', TextType::class, array('required' => false,'data' => $session->get('filtroTteDespachoCodigo')))
            ->add('cboGuiaTipoRel', EntityType::class, $em->getRepository(TteGuiaTipo::class)->llenarCombo())
            ->add('cboOperacionIngresoRel', EntityType::class, $em->getRepository(TteOperacion::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                $session = new session;
                $session->set('filtroTteDespachoCodigo', $form->get('txtDespachoCodigo')->getData());
                $arGuiaTipo = $form->get('cboGuiaTipoRel')->getData();
                if ($arGuiaTipo) {
                    $session->set('filtroTteGuiaCodigoGuiaTipo', $arGuiaTipo->getCodigoGuiaTipoPk());
                } else {
                    $session->set('filtroTteGuiaCodigoGuiaTipo', null);
                }
                $arGuiaTipo = $form->get('cboOperacionIngresoRel')->getData();
                if ($arGuiaTipo) {
                    $session->set('filtroTteGuiaCodigoOperacionIngreso', $arGuiaTipo->getCodigoOperacionPk());
                } else {
                    $session->set('filtroTteGuiaCodigoOperacionIngreso', null);
                }
            }
            if ($form->get('btnGenerar')->isClicked()) {
                $arrGuias = $request->request->get('chkSeleccionar');
                $arrFacturas = $this->getDoctrine()->getRepository(TteGuia::class)->generarFactura($arrGuias, $this->getUser()->getUsername());

                //Para que despues del proceso se contabilicen automaticamente
                $arConfiguracion = $em->getRepository(GenConfiguracion::class)->contabilidadAutomatica();
                if ($arConfiguracion['contabilidadAutomatica']) {
                    if($arrFacturas) {
                        $arrFacturasCodigo = array();
                        foreach ($arrFacturas as $arrFactura) {
                            $arFactura = $em->getRepository(TteFactura::class)->findOneBy(array('codigoFacturaTipoFk' => $arrFactura['tipo'], 'numero' => $arrFactura['numero']));
                            if($arFactura) {
                                $arrFacturasCodigo[] = $arFactura->getCodigoFacturaPk();
                            }
                        }
                        if($arrFacturasCodigo) {
                            $em->getRepository(TteFactura::class)->contabilizar($arrFacturasCodigo);
                        }
                    }
                }
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteGuia::class)->listaGenerarFactura()->getQuery()->getResult(), "PendienteGenrarFactura");
            }
        }
        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->listaGenerarFactura(), $request->query->getInt('page', 1), 500);
        return $this->render('transporte/proceso/transporte/guia/generarFactura.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()
        ]);
    }
}

