<?php

namespace App\Controller\Transporte\Utilidad\Servicio\Guia;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class NotificarEstadoController extends MaestroController
{
    public $tipo = "utilidad";
    public $proceso = "tteu0003";


    /**
     * @Route("/transporte/uti/control/novedad/notificar/estado", name="transporte_uti_servicio_novedad_notificar_estado")
     */
    public function lista(Request $request, \Swift_Mailer $mailer, PaginatorInterface $paginator)
    {
        $session=new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->formularioFiltro();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if($form->get('btnFiltrar')->isSubmitted()){
                    $session->set('notificarEstadoCliente',$form->get('cliente')->getData());
                }
                if ($request->request->get('OpSinReportar')) {
                    $codigo = $request->request->get('OpSinReportar');
                    $arCliente = $em->getRepository(TteCliente::class)->find($codigo);
                    $destinatario = explode(';', strtolower($arCliente->getCorreo()));
                    $arGuiaEstados = $em->getRepository(TteGuia::class)->findBy(['codigoClienteFk'=>$codigo]);
                    $cuerpo = $this->render('transporte/utilidad/servicio/guia/correoEstado.html.twig', [
                        'arGuiaEstados' => $arGuiaEstados,
                        'form' => $form->createView()]);
                    $message = (new \Swift_Message('Reporte estado guias'))
                        ->setFrom('infologicuartas@gmail.com')
                        ->setTo("$destinatario")
                        ->setBody(
                            $cuerpo,
                            'text/html'
                        );
                    $mailer->send($message);

                    return $this->redirect($this->generateUrl('transporte_uti_servicio_novedad_notificar_estado'));

                }
            }
        }
        $query = $this->getDoctrine()->getRepository(TteGuia::class)->estadoGuiaCliente();
        $arEstadoGuia = $paginator->paginate($query, $request->query->getInt('page', 1), 500);
        return $this->render('transporte/utilidad/servicio/guia/notificarEstado.html.twig', [
            'arEstadoGuias' => $arEstadoGuia,
            'form' => $form->createView()]);
    }

        private function formularioFiltro()
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();

        $form = $this->createFormBuilder()
            ->add("cliente",TextType::class,[
                'data'=>$session->get('notificarEstadoCliente'),
                'required'=>false,
            ])
            ->add("btnFiltrar", SubmitType::class, ['label' => "Filtro", 'attr' => ['class' => 'filtrar btn btn-default btn-sm', 'style' => 'float:right']])

            ->getForm();
        return $form;
    }
}
