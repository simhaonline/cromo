<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class GenResponsabilidadFiscal extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arrResponsabilidades = array(
            ['codigo' =>'O-06', 'nombre'=> 'Ingresos y patrimonio',                                                                                 'codigoInterface' => 'O-06'],
            ['codigo' =>'O-07', 'nombre'=> 'Retención en la fuente a título de renta',                                                              'codigoInterface' => 'O-07'],
            ['codigo' =>'O-08', 'nombre'=> 'Retención timbre nacional',                                                                             'codigoInterface' => 'O-08'],
            ['codigo' =>'O-09', 'nombre'=> 'Retención en la fuente en el impuesto sobre las ventas',                                                'codigoInterface' => 'O-09'],
            ['codigo' =>'O-11', 'nombre'=> 'Ventas régimen común',                                                                                  'codigoInterface' => 'O-11'],
            ['codigo' =>'O-12', 'nombre'=> 'Ventas régimen simplificado',                                                                           'codigoInterface' => 'O-12'],
            ['codigo' =>'O-13', 'nombre'=> 'Gran contribuyente',                                                                                    'codigoInterface' => 'O-13'],
            ['codigo' =>'O-14', 'nombre'=> 'Informante de exógena',                                                                                 'codigoInterface' => 'O-14'],
            ['codigo' =>'O-15', 'nombre'=> 'Autorretenedor',                                                                                        'codigoInterface' => 'O-15'],
            ['codigo' =>'O-16', 'nombre'=> 'Obligación de facturar por ingresos de bienes y/o servicios excluidos',                                 'codigoInterface' => 'O-16'],
            ['codigo' =>'O-17', 'nombre'=> 'Profesionales de compra y venta de divisas',                                                            'codigoInterface' => 'O-17'],
            ['codigo' =>'O-19', 'nombre'=> 'Productor y/o exportador de bienes exentos',                                                            'codigoInterface' => 'O-19'],
            ['codigo' =>'O-22', 'nombre'=> 'Obligado a cumplir deberes formales a nombre de terceros',                                              'codigoInterface' => 'O-22'],
            ['codigo' =>'O-23', 'nombre'=> 'Agente de retención en el impuesto sobre las ventas',                                                   'codigoInterface' => 'O-23'],
            ['codigo' =>'O-32', 'nombre'=> 'Impuesto Nacional a la Gasolina y al ACPM',                                                             'codigoInterface' => 'O-32'],
            ['codigo' =>'O-33', 'nombre'=> 'Impuesto Nacional al consumo',                                                                          'codigoInterface' => 'O-33'],
            ['codigo' =>'O-34', 'nombre'=> 'Régimen simplificado impuesto nacional consumo rest y bares',                                           'codigoInterface' => 'O-34'],
            ['codigo' =>'O-36', 'nombre'=> 'Establecimiento Permanente',                                                                            'codigoInterface' => 'O-36'],
            ['codigo' =>'O-37', 'nombre'=> 'Obligado a Facturar Electrónicamente Modelo 2242',                                                      'codigoInterface' => 'O-37'],
            ['codigo' =>'O-38', 'nombre'=> 'Facturación Electrónica Voluntaria Modelo 2242',                                                        'codigoInterface' => 'O-38'],
            ['codigo' =>'O-39', 'nombre'=> 'Proveedor de Servicios Tecnológicos PST Modelo 2242',                                                   'codigoInterface' => 'O-39'],
            ['codigo' =>'O-99', 'nombre'=> 'Otro tipo de obligado',                                                                                 'codigoInterface' => 'O-99'],
            ['codigo' =>'R-12-PN', 'nombre'=> 'Factor PN',                                                                                          'codigoInterface' => 'R-12-PN'],
            ['codigo' =>'R-16-PN', 'nombre'=> 'Mandatario PN',                                                                                      'codigoInterface' => 'R-16-PN'],
            ['codigo' =>'R-25-PN', 'nombre'=> 'Agente Interventor PN',                                                                              'codigoInterface' => 'R-25-PN'],
            ['codigo' =>'R-06-PJ', 'nombre'=> 'Apoderado especial PJ',                                                                              'codigoInterface' => 'R-06-PJ'],
            ['codigo' =>'R-07-PJ', 'nombre'=> 'Apoderado general PJ',                                                                               'codigoInterface' => 'R-07-PJ'],
            ['codigo' =>'R-12-PJ', 'nombre'=> 'Factor PJ',                                                                                          'codigoInterface' => 'R-12-PJ'],
            ['codigo' =>'R-16-PJ', 'nombre'=> 'Mandatario PJ',                                                                                      'codigoInterface' => 'R-16-PJ'],
            ['codigo' =>'R-99-PJ', 'nombre'=> 'Otro tipo de responsable PJ',                                                                        'codigoInterface' => 'R-99-PJ'],
            ['codigo' =>'A-01', 'nombre'=> 'Agente de carga internacional',                                                                         'codigoInterface' => 'A-01'],
            ['codigo' =>'A-02', 'nombre'=> 'Agente marítimo',                                                                                       'codigoInterface' => 'A-02'],
            ['codigo' =>'A-03', 'nombre'=> 'Almacén general de depósito',                                                                           'codigoInterface' => 'A-03'],
            ['codigo' =>'A-04', 'nombre'=> 'Comercializadora internacional (C.I.)',                                                                 'codigoInterface' => 'A-04'],
            ['codigo' =>'A-05', 'nombre'=> 'Comerciante de la zona aduanera especial de Inírida, Puerto Carrentilde;o, Cumaribo y Primavera',       'codigoInterface' => 'A-05'],
            ['codigo' =>'A-06', 'nombre'=> 'Comerciantes de la zona de régimen aduanero especial de Leticia',                                       'codigoInterface' => 'A-06'],
            ['codigo' =>'A-07', 'nombre'=> 'Comerciantes de la zona de régimen aduanero especial de Maicao, Uribia y Manaure',                      'codigoInterface' => 'A-07'],
        );


        foreach ($arrResponsabilidades as $arrResponsabilidad){
            $arResponsabilidadFiscal = $manager->getRepository(\App\Entity\General\GenResponsabilidadFiscal::class)->find($arrResponsabilidad['codigo']);
            if(!$arResponsabilidadFiscal) {
                $arResponsabilidadFiscal = new \App\Entity\General\GenResponsabilidadFiscal();
                $arResponsabilidadFiscal->setCodigoResponsabilidadFiscalPk($arrResponsabilidad['codigo']);
                $arResponsabilidadFiscal->setNombre($arrResponsabilidad['nombre']);
                $arResponsabilidadFiscal->setCodigoInterface($arrResponsabilidad['codigoInterface']);
                $manager->persist($arResponsabilidadFiscal);
            }
        }

        $manager->flush();
    }
}
