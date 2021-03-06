<?php

namespace App\Controller\Tesoreria\Administracion;

use App\Controller\BaseController;
use App\Controller\MaestroController;
use App\Entity\Tesoreria\TesTercero;
use App\Form\Type\Tesoreria\TerceroType;
use App\General\General;
use App\Utilidades\Estandares;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TerceroController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TesTercero";

    protected $clase = TesTercero::class;
    protected $claseNombre = "TesTercero";
    protected $modulo = "Tesoreria";
    protected $funcion = "Administracion";
    protected $grupo = "Tercero";
    protected $nombre = "Tercero";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/tesoreria/administracion/tercero/tercero/lista", name="tesoreria_administracion_tercero_tercero_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('codigoTerceroPk', TextType::class, array('required' => false))
            ->add('nombreCorto', TextType::class, array('required' => false))
            ->add('numeroIdentificacion', IntegerType::class, array('required' => false))
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
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
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TesTercero::class)->lista($raw), "Terceros");
            }
            if ($form->get('btnEliminar')->isClicked()) {
            }
        }
        $arTerceros = $paginator->paginate($em->getRepository(TesTercero::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('tesoreria/administracion/tercero/lista.html.twig', [
            'arTerceros' => $arTerceros,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/compra/administracion/proveedor/proveedor/nuevo/{id}", name="tesoreria_administracion_tercero_tercero_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTercero = new TesTercero();
        if ($id != 0) {
            $arTercero = $em->getRepository(TesTercero::class)->find($id);
            if (!$arTercero) {
                return $this->redirect($this->generateUrl('tesoreria_administracion_tercero_tercero_lista'));
            }
        }
        $form = $this->createForm(TerceroType::class, $arTercero);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arTercero = $form->getData();
                $em->persist($arTercero);
                $em->flush();
                return $this->redirect($this->generateUrl('tesoreria_administracion_tercero_tercero_lista'));
            }
        }
        return $this->render('tesoreria/administracion/tercero/nuevo.html.twig', [
            'arTercero' => $arTercero,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("tesoreria/administracion/tercero/tercero/detalle/{id}", name="tesoreria_administracion_tercero_tercero_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTercero = $em->getRepository($this->clase)->find($id);
        return $this->render('tesoreria/administracion/tercero/detalle.html.twig', [
            'arTercero' => $arTercero,
        ]);
    }

    public function getFiltros($form)
    {
        return $filtro = [
            'codigoTerceroPk' => $form->get('codigoTerceroPk')->getData(),
            'nombreCorto' => $form->get('nombreCorto')->getData(),
            'numeroIdentificacion' => $form->get('numeroIdentificacion')->getData(),
        ];
    }

}
