<?php

namespace App\Controller\Transporte\Informe\Servicio\Novedad;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteNovedad;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class PendienteAtenderController extends MaestroController
{
    public $tipo = "Informe";
    public $proceso = "ttei0001";



    /**
    * @Route("/transporte/inf/control/novedad/pendiente/atender", name="transporte_inf_servicio_novedad_pendiente_atender")
    */    
    public function lista(Request $request,  PaginatorInterface $paginator)
    {

        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('btnAtender')->isClicked()) {
                    $arrNovedades = $request->request->get('chkSeleccionar');
                    $arrControles = $request->request->All();
                    $respuesta = $this->getDoctrine()->getRepository(TteNovedad::class)->setAtender($arrNovedades, $arrControles);
                }
            }
        }
        $query = $this->getDoctrine()->getRepository(TteNovedad::class)->pendienteAtender();
        $arNovedades = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        return $this->render('transporte/informe/servicio/novedad/pendienteAtender.html.twig', [
            'arNovedades' => $arNovedades,
            'form' => $form->createView()]);
    }

    private function filtrar($form)
    {
        $session = new session;
        $arRuta = $form->get('rutaRel')->getData();
        if ($arRuta) {
            $session->set('filtroTteCodigoRuta', $arRuta->getCodigoRutaPk());
        } else {
            $session->set('filtroTteCodigoRuta', null);
        }
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;

        $form = $this->createFormBuilder()
            ->add('btnAtender', SubmitType::class, array('label' => 'Atender'))
            ->getForm();
        return $form;
    }


}

