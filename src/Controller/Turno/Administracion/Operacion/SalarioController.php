<?php


namespace App\Controller\Turno\Administracion\Operacion;

use App\Controller\MaestroController;
use App\Entity\Turno\TurSalario;
use App\Entity\Turno\TurSecuencia;
use App\Form\Type\Turno\SalarioType;
use App\General\General;
use App\Utilidades\Estandares;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SalarioController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TurSalario";


    protected $clase = TurSalario::class;
    protected $claseNombre = "TurSalario";
    protected $modulo = "Turno";
    protected $funcion = "Administracion";
    protected $grupo = "Operacion";
    protected $nombre = "Salario";


    /**
     * @Route("/turno/administracion/operacion/salario/lista", name="turno_administracion_operacion_salario_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('nombre', TextType::class, array('required' => false))
            ->add('codigoSalarioPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(TurSalario::class)->lista($raw), "SALARIOS");
            }
            if ($form->get('btnEliminar')->isClicked()) {
            }
        }
        $arSalarios = $paginator->paginate($em->getRepository(TurSalario::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('turno/administracion/operacion/salario/lista.html.twig', [
            'arSalarios' => $arSalarios,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/administracion/operacion/salario/nuevo/{id}", name="turno_administracion_operacion_salario_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSalario = $em->getRepository(TurSalario::class)->find($id);
        if (is_null($arSalario)){
            $arSalario = new TurSalario();
        }
        $form = $this->createForm(SalarioType::class, $arSalario);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSalario = $form->getData();
                $em->persist($arSalario);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_operacion_salario_detalle', array('id' => $arSalario->getCodigoSalarioPk())));
            }
        }
        return $this->render('turno/administracion/operacion/salario/nuevo.html.twig', [
            'arSalario' => $arSalario,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/turno/administracion/operacion/salario/detalle/{id}", name="turno_administracion_operacion_salario_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSalario = $em->getRepository(TurSalario::class)->find($id);
        return $this->render('turno/administracion/operacion/salario/detalle.html.twig', [
            'arSalario' => $arSalario,
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'nombre' => $form->get('nombre')->getData(),
            'codigoSalario' => $form->get('codigoSalarioPk')->getData()
        ];
        return $filtro;
    }
}