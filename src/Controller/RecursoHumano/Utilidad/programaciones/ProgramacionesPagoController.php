<?php


namespace App\Controller\RecursoHumano\Utilidad\programaciones;

use App\Controller\MaestroController;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuProgramacion;
use App\Entity\RecursoHumano\RhuProgramacionDetalle;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

    class ProgramacionesPagoController extends MaestroController
{
        public $tipo = "utilidad";
        public $proceso = "rhuu0002";


    /**
     * @Route("/recursohumano/utilidad/intercambio/programacion", name="recursohumano_utilidad_intercambio_programacion")
     */
    public function programaciones(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($request->request->get('btnTransferir')) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arConfiguracion =  $em->getRepository(GenConfiguracion::class)->find(1);
                $codigoProgramacion = $request->request->get('btnTransferir');
                $arrDatos = [
                    'codigoEmpresa' => $arConfiguracion->getCodigoEmpresaOxigeno(),
                    'arrEmpleados' => $em->getRepository(RhuProgramacionDetalle::class)->empleadosProgramacion($codigoProgramacion),
                    'arrPagos' => $em->getRepository(RhuPago::class)->programacion($codigoProgramacion)
                ];

                $datosJson = json_encode($arrDatos);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $arConfiguracion->getWebServiceOxigenoUrl().'/api/pago/nuevo');
                //curl_setopt($ch, CURLOPT_URL, 'http://localhost/oxigeno/public/index.php/api/pago/nuevo');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $datosJson);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($datosJson))
                );
                $respuesta = json_decode(curl_exec($ch));
                if($respuesta->estado == 'ok') {
                    $arProgramacion = $em->getRepository(RhuProgramacion::class)->find($codigoProgramacion);
                    $arProgramacion->setEstadoIntercambio(1);
                    $em->persist($arProgramacion);
                    $em->flush();
                } else {
                    Mensajes::error($respuesta->mensajeError);
                }
            }
        }
        $arProgrmaciones = $paginator->paginate($em->getRepository(RhuProgramacion::class)->intercambio(), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/utilidad/programacion/programacion.html.twig', [
            'arProgrmaciones' => $arProgrmaciones,
            'form' => $form->createView()
        ]);
    }

}