<?php

namespace App\Controller\RecursoHumano\Administracion\Configuracion;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoHora;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\Transporte\TteConfiguracion;
use App\Form\Type\RecursoHumano\ConfiguracionType;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConfiguracionController extends Controller
{
    /**
     * @param Request $request
     * @param $id integer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recursohumano/administracion/configuracion/configuracion/lista/{id}", name="recursohumano_administracion_configuracion_configuracion_lista")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
        if (!$arConfiguracion) {
            $arConfiguracion = new RhuConfiguracion();
            $arConfiguracion->setCodigoConfiguracionPk(1);
        }
        $arrConceptoRelDiurna = $this->generarConceptoRel('D');
        $arrConceptoRelDescanso = $this->generarConceptoRel('DS');
        $arrConceptoRelNocturna = $this->generarConceptoRel('N');
        $arrConceptoRelFestivaDiurna = $this->generarConceptoRel('FD');
        $arrConceptoRelFestivaNocturna = $this->generarConceptoRel('FN');
        $arrConceptoRelExtrasOrdinariasDiurnas = $this->generarConceptoRel('EOD');
        $arrConceptoRelExtrasOrdinariasNocturnas = $this->generarConceptoRel('EON');
        $arrConceptoRelExtrasFestivasDiurnas = $this->generarConceptoRel('EFD');
        $arrConceptoRelExtrasFestivasNocturnas = $this->generarConceptoRel('EFN');
        $arrConceptoRelRecargoNocturno = $this->generarConceptoRel('RN');
        $arrConceptoRelRecargoFestivoDiurno = $this->generarConceptoRel('RFD');
        $arrConceptoRelRecargoFestivoNocturno = $this->generarConceptoRel('RFN');
//        $arrConceptoCesantiasRel = $this->generarConceptoRel('CES');
        $formConceptoHora = $this->get('form.factory')->createNamedBuilder('conceptoHora')
            ->add('DConceptoRel', EntityType::class, $arrConceptoRelDiurna)
            ->add('DSConceptoRel', EntityType::class, $arrConceptoRelDescanso)
            ->add('NConceptoRel', EntityType::class, $arrConceptoRelNocturna)
            ->add('FDConceptoRel', EntityType::class, $arrConceptoRelFestivaDiurna)
            ->add('FNConceptoRel', EntityType::class, $arrConceptoRelFestivaNocturna)
            ->add('EODConceptoRel', EntityType::class, $arrConceptoRelExtrasOrdinariasDiurnas)
            ->add('EONConceptoRel', EntityType::class, $arrConceptoRelExtrasOrdinariasNocturnas)
            ->add('EFDConceptoRel', EntityType::class, $arrConceptoRelExtrasFestivasDiurnas)
            ->add('EFNConceptoRel', EntityType::class, $arrConceptoRelExtrasFestivasNocturnas)
            ->add('RNConceptoRel', EntityType::class, $arrConceptoRelRecargoNocturno)
            ->add('RFDConceptoRel', EntityType::class, $arrConceptoRelRecargoFestivoDiurno)
            ->add('RFNConceptoRel', EntityType::class, $arrConceptoRelRecargoFestivoNocturno)
//            ->add('CESConceptoRel', EntityType::class, $arrConceptoCesantiasRel)
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar'])
            ->getForm();
        $formConceptoHora->handleRequest($request);
        $formConfiguracion = $this->createForm(ConfiguracionType::class, $arConfiguracion);
        $formConfiguracion->handleRequest($request);
        if ('POST' === $request->getMethod()) {
            if ($request->request->has('conceptoHora')) {
                if ($formConceptoHora->isValid()) {
                    foreach ($formConceptoHora->getViewData() as $nombreHora => $arConceptoRel) {
                        $this->guardarConceptoRel($nombreHora, $arConceptoRel);
                    }
                    $em->flush();
                }
            }
            Mensajes::success('Configuracion actualizada correctamente');
        }

        return $this->render('recursohumano/administracion/configuracion/configuracion.html.twig', [
            'arConfiguracion' => $arConfiguracion,
            'formConceptoHora' => $formConceptoHora->createView(),
            'formConfiguracion' => $formConfiguracion->createView()
        ]);
    }

    /**
     * @param $nombreHora string
     * @return mixed
     */
    private function generarConceptoRel($nombreHora)
    {
        $em = $this->getDoctrine()->getManager();
        $arrConceptoRel = [
            'class' => RhuConcepto::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('er')
                    ->orderBy('er.nombre', 'ASC');
            },
            'required' => true,
            'choice_label' => function ($er) {
                $campo = $er->getCodigoConceptoPk() . " - " . $er->getNombre();
                return $campo;
            },
            'data' => '',
            'empty_data' => ''
        ];
        /** @var  $arConcepto RhuConcepto */
        $arConcepto = $em->getRepository(RhuConceptoHora::class)->find($nombreHora)->getConceptoRel();
        if ($arConcepto) {
            $arrConceptoRel['data'] = $em->getReference(RhuConcepto::class, $arConcepto->getCodigoConceptoPk());
        }
        return $arrConceptoRel;
    }

    /**
     * @param $nombreHora
     * @param $arConcepto RhuConcepto
     */
    private function guardarConceptoRel($nombreHora, $arConcepto)
    {
        $em = $this->getDoctrine()->getManager();
        $codigoConceptoHora = explode('ConceptoRel', $nombreHora)[0];
        $arConceptoHora = $this->getDoctrine()->getManager()->getRepository(RhuConceptoHora::class)->find($codigoConceptoHora);
        if ($arConceptoHora) {
            $arConceptoHora->setConceptoRel($arConcepto);
            $em->persist($arConceptoHora);
        }
    }
}