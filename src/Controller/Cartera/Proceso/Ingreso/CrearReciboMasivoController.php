<?php

namespace App\Controller\Cartera\Proceso\Ingreso;

use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Form\Type\Cartera\ReciboType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CrearReciboMasivoController extends AbstractController
{
    /**
     * @param Request $request
     * @param TokenStorageInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/proceso/ingreso/recibomasivo/lista", name="cartera_proceso_ingreso_recibomasivo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator,TokenStorageInterface $user)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('cuentaCobrarTipo', EntityType::class, $em->getRepository(CarCuentaCobrarTipo::class)->llenarCombo())
            ->add('numeroReferencia', TextType::class, ['required' => false, 'data' => $session->get('filtroCarNumeroReferencia'), 'attr' => ['class' => 'form-control']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => "Filtro", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        $arReciboInicial = new CarRecibo();
        $arReciboInicial->setFechaPago(new \DateTime('now'));
        $formRecibo = $this->createForm(ReciboType::class, $arReciboInicial,[
            'action' => $this->generateUrl('cartera_proceso_ingreso_recibomasivo_lista'),
            'method' => 'GET',
        ]);
        $formRecibo->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(CarCuentaCobrar::class)->crearReciboMasivoLista($raw)->getQuery()->execute(), "cuenta cobrar");
            }
        }
        if ($formRecibo->isSubmitted()) {
            if ($formRecibo->get('guardar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                if ($arrSeleccionados) {
                    $arrRecibos = [];
                    foreach ($arrSeleccionados as $codigoCuentaCobrar) {
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($codigoCuentaCobrar);
                        if ($arCuentaCobrar) {
                            /** @var  $arrDatos CarRecibo */
                            $arrDatos = $formRecibo->getData();
                            $arRecibo = new CarRecibo();
                            $arRecibo->setCodigoAsesorFk($arrDatos->getAsesorRel() ? $arrDatos->getAsesorRel()->getCodigoAsesorPk() : null);
                            $arRecibo->setAsesorRel($arrDatos->getAsesorRel());
                            $arRecibo->setCodigoReciboTipoFk($arrDatos->getReciboTipoRel()->getCodigoReciboTipoPk());
                            $arRecibo->setComentarios($arrDatos->getComentarios());
                            $arRecibo->setCodigoCuentaFk($arrDatos->getCuentaRel()->getCodigoCuentaPk());
                            $arRecibo->setCuentaRel($arrDatos->getCuentaRel());
                            $arRecibo->setReciboTipoRel($arrDatos->getReciboTipoRel());
                            $arRecibo->setFecha(new \DateTime('now'));
                            $arRecibo->setFechaPago($arrDatos->getFechaPago());
                            $arRecibo->setCodigoClienteFk($arCuentaCobrar->getCodigoClienteFk());
                            $arRecibo->setClienteRel($arCuentaCobrar->getClienteRel());
                            $arRecibo->setVrPago($arCuentaCobrar->getVrSaldo());
                            $arRecibo->setVrPagoTotal($arCuentaCobrar->getVrSaldo());
                            $arRecibo->setUsuario($user->getToken()->getUsername());
                            $arRecibo->setSoporte($arrDatos->getSoporte());
                            $em->persist($arRecibo);

                            $arrRecibos[] = $arRecibo;
                            $arReciboDetalle = new CarReciboDetalle();
                            $arReciboDetalle->setCodigoReciboFk($arRecibo->getCodigoReciboPk());
                            $arReciboDetalle->setReciboRel($arRecibo);
                            $arReciboDetalle->setCodigoCuentaCobrarFk($arCuentaCobrar->getCodigoCuentaCobrarPk());
                            $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                            $arReciboDetalle->setVrRetencionFuente($arCuentaCobrar->getVrRetencionFuente());
                            $arReciboDetalle->setVrPago($arCuentaCobrar->getVrSaldo());
                            $arReciboDetalle->setVrPagoAfectar($arCuentaCobrar->getVrSaldo());
                            $arReciboDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                            $arReciboDetalle->setCodigoCuentaCobrarTipoFk($arCuentaCobrar->getCodigoCuentaCobrarTipoFk());
                            $arReciboDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                            $arReciboDetalle->setAsesorRel($arRecibo->getAsesorRel());
                            $arReciboDetalle->setOperacion(1);
                            $em->persist($arReciboDetalle);
                        }
                    }
                    $em->flush();
                    $this->cerrarRecibo($arrRecibos);
                } else {
                    Mensajes::error("No ha seleccionado cuenta por cobrar");
                }
                return $this->redirect($this->generateUrl('cartera_proceso_ingreso_recibomasivo_lista'));
            }
        }
        $arCuentasCobrar = $paginator->paginate($em->getRepository(CarCuentaCobrar::class)->crearReciboMasivoLista($raw), $request->query->getInt('page', 1), 100);
        return $this->render('cartera/proceso/contabilidad/crearrecibomasivo/lista.html.twig', [
            'arCuentasCobrar' => $arCuentasCobrar,
            'form' => $form->createView(),
            'formRecibo' => $formRecibo->createView()
        ]);
    }

    /**
     * @param $arrRecibos array
     */
    private function cerrarRecibo($arrRecibos)
    {
        $em = $this->getDoctrine()->getManager();
        if ($arrRecibos) {
            /** @var  $arRecibo CarRecibo */
            foreach ($arrRecibos as $arRecibo) {
                $em->getRepository(CarRecibo::class)->autorizar($arRecibo);
                $em->getRepository(CarRecibo::class)->aprobar($arRecibo);
            }
        }
    }

    public function getFiltros($form)
    {
        $filtro = [
            'numeroReferencia' => $form->get('numeroReferencia')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arCuentaCobrarTipo = $form->get('cuentaCobrarTipo')->getData();

        if (is_object($arCuentaCobrarTipo)) {
            $filtro['cuentaCobrarTipo'] = $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk();
        } else {
            $filtro['cuentaCobrarTipo'] = $arCuentaCobrarTipo;
        }

        return $filtro;

    }
}
