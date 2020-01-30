<?php

namespace App\Controller\Inventario\Utilidad\Inventario\Factura;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\General\GenAsesor;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Inventario\InvMovimientoDetalle;
use App\Form\Type\Inventario\CorreccionFacturaType;
use App\Formato\Inventario\ExistenciaLote;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CorregirFacturaController extends MaestroController
{

    public $tipo = "utilidad";
    public $proceso = "invu0002";



    /**
     * @param Request $request
     * @return Response
     * @Route("/inventario/utilidad/inventario/factura/corregirfactura", name="inventario_utilidad_inventario_factura_corregirfactura")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNumeroFactura', TextType::class)
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))

            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
        }
        $arCorregirFacturas = $paginator->paginate($em->getRepository(InvMovimiento::class)->corregirFactura($raw), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/utilidad/inventario/factura/corregirFactura.html.twig', [
            'arCorregirFacturas' => $arCorregirFacturas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/utilidad/inventario/factura/corregirfactura/nuevo/{id}", name="inventario_utilidad_inventario_factura_corregirfactura_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arFactura = $em->getRepository(InvMovimiento::class)->find($id);
        $form = $this->createForm(CorreccionFacturaType::class, $arFactura);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('guardar')->isClicked()) {
                    $em->persist($arFactura);
                    $em->flush();
                }
            }
            return $this->redirect($this->generateUrl('inventario_utilidad_inventario_factura_corregirfactura'));
        }
        return $this->render('inventario/utilidad/inventario/factura/nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }

    public function getFiltros($form)
    {
        $filtro = [
            'nuemroFactura' =>  $form->get('txtNumeroFactura')->getData()
        ];


        return $filtro;

    }

}

