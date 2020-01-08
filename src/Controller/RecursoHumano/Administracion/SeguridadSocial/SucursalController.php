<?php


namespace App\Controller\RecursoHumano\Administracion\SeguridadSocial;


use App\Entity\RecursoHumano\RhuSucursal;
use App\Form\Type\RecursoHumano\SucursalType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;

class SucursalController extends AbstractController
{
    /**
     * @Route("recursohumano/administracion/seguridadsocial/sucursal/lista", name="recursohumano_administracion_seguridadsocial_sucursal_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoSucursalPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('estadoActivo', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
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
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuSucursal::class)->lista($raw), "Sucursal");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuSucursal::class)->eliminar($arrSeleccionados);
            }
        }
        $arSucursales = $paginator->paginate($em->getRepository(RhuSucursal::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/seguridadsocial/sucursal/lista.html.twig',[
            'arSucursales' => $arSucursales,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seguridadsocial/sucursal/nuevo/{id}", name="recursohumano_administracion_seguridadsocial_sucursal_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSucursal = new RhuSucursal();
        if ($id != 0) {
            $arSucursal = $em->getRepository(RhuSucursal::class)->find($id);
            if (!$arSucursal) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_seguridadsocial_sucursal_lista'));
            }
        }
        $form = $this->createForm(SucursalType::class, $arSucursal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSucursal = $form->getData();
                $em->persist($arSucursal);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_seguridadsocial_sucursal_detalle', array('id' => $arSucursal->getCodigoSucursalPK())));
            }
        }
        return $this->render('recursohumano/administracion/seguridadsocial/sucursal/nuevo.html.twig',[
            'arSucursal' => $arSucursal,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/administracion/seguridadsocial/sucursal/detalle/{id}", name="recursohumano_administracion_seguridadsocial_sucursal_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSucursal = $em->getRepository(RhuSucursal::class)->find($id);
        return $this->render('recursohumano/administracion/seguridadsocial/sucursal/detalle.html.twig',[
            'arSucursal' => $arSucursal,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoSucursal' => $form->get('codigoSucursalPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'estadoActivo' => $form->get('estadoActivo')->getData(),
        ];

        return $filtro;

    }

}