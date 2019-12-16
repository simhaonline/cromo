<?php


namespace App\Controller\Turno\Buscar;


use App\Entity\Turno\TurNovedad;
use App\Entity\Turno\TurNovedadTipo;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class NovedadController extends AbstractController
{
    /**
     * @param Request $request
     * @param $campoCodigo
     * @param $campoNombre
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/turno/buscar/novedad/{campoCodigo}/{campoNombre}", name="turno_buscar_novedad")
     */
    public function lista(Request $request, PaginatorInterface $paginator,$campoCodigo, $campoNombre)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteNombre')))
            ->add('txtCodigo', TextType::class, array('required' => false, 'data' => $session->get('filtroTurClienteCodigo')))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        $raw=[];
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros']=[
                    'codigoNovedadTipo'=> $form->get('txtCodigo')->getData(),
                    'nombre'=> $form->get('txtNombre')->getData()
                ];

            }
        }
        $arNovedades = $paginator->paginate($em->getRepository(TurNovedadTipo::class)->lista($raw), $request->query->get('page', 1), 20);
        return $this->render('turno/buscar/novedades.html.twig', array(
            'arNovedades' => $arNovedades,
            'campoCodigo' => $campoCodigo,
            'campoNombre' => $campoNombre,
            'form' => $form->createView()
        ));
    }
}