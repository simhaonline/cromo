<?php

namespace App\Controller\Transporte\Utilidad\Transporte;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaCarga;
use App\Form\Type\Transporte\GuiaCorreccionType;
use App\Form\Type\Transporte\GuiaCorreccionValoresType;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CorreccionGuiaValoresController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "tteu0005";


    protected $procestoTipo = "U";
    protected $nombreProceso = "CorreccionGuias";
    protected $modulo = "Transporte";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/utilidad/transporte/correccionguiavalores/lista", name="transporte_utilidad_transporte_correccion_guia_valores_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('txtDocumento', TextType::class, array('data' => $session->get('filtroTteGuiaDocumento')))
            ->add('txtCodigo', TextType::class, array('data' => $session->get('filtroTteGuiaCodigo')))
            ->add('txtNumero', TextType::class, array('data' => $session->get('filtroTteGuiaNumero')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session = new session;
                $session->set('filtroTteGuiaDocumento', $form->get('txtDocumento')->getData());
                $session->set('filtroTteGuiaNumero', $form->get('txtNumero')->getData());
                $session->set('filtroTteGuiaCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
            }
        }
        $arGuias = $paginator->paginate($arGuias = $em->getRepository(TteGuia::class)->correccionGuiaValores(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/utilidad/transporte/correccionGuiaValores/lista.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/transporte/utilidad/transporte/correccionguiavalores/nuevo/{id}", name="transporte_utilidad_transporte_correccion_guia_valores_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($id);
        if ($arGuia->getEstadoFacturado()) {
            Mensajes::error('La guia se encuentra facturada, no se puede editar');
            return $this->redirect($this->generateUrl('transporte_utilidad_transporte_correccion_guia_lista'));
        }
        $form = $this->createForm(GuiaCorreccionValoresType::class, $arGuia);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('guardar')->isClicked()) {
                    $em->persist($arGuia);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('transporte_utilidad_transporte_correccion_guia_valores_lista'));
            }

        }
        return $this->render('transporte/utilidad/transporte/correccionGuiaValores/nuevo.html.twig', array(
            'arGuia' => $arGuia,
            'form' => $form->createView()));
    }

}

