<?php

namespace App\Controller\Transporte\Utilidad\Servicio\Novedad;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteCliente;
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

class NotificarController extends MaestroController
{
    public $tipo = "utilidad";
    public $proceso = "tteu0001";



   /**
    * @Route("/transporte/uti/control/novedad/notificar", name="transporte_uti_servicio_novedad_notificar")
    */    
    public function lista(Request $request, \Swift_Mailer $mailer, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $query = $this->getDoctrine()->getRepository(TteNovedad::class)->pendienteSolucionarCliente();
        $arNovedades = $paginator->paginate($query, $request->query->getInt('page', 1),500);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($request->request->get('OpSinReportar')) {
                    $codigo = $request->request->get('OpSinReportar');
                    $arCliente = $em->getRepository(TteCliente::class)->find($codigo);
                    $destinatario = explode(';', strtolower($arCliente->getCorreo()));
                    $arNovedadesPendientes = $em->getRepository(TteNovedad::class)->utilidadNotificar($codigo);
                    $cuerpo = $this->render('transporte/utilidad/servicio/novedad/correo.html.twig', [
                        'arNovedades' => $arNovedadesPendientes,
                        'form' => $form->createView()]);
                    $message = (new \Swift_Message('Reporte novedades pendientes'))
                        ->setFrom('infologicuartas@gmail.com')
                        ->setTo($destinatario)
                        ->setBody(
                            $cuerpo,
                            'text/html'
                        );
                    $mailer->send($message);

                    return $this->redirect($this->generateUrl('transporte_uti_servicio_novedad_notificar'));

                }
            }
        }

        return $this->render('transporte/utilidad/servicio/novedad/notificar.html.twig', [
            'arNovedades' => $arNovedades,
            'form' => $form->createView()]);
    }

    private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new session;

        $form = $this->createFormBuilder()

            ->getForm();
        return $form;
    }


}

