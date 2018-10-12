<?php

namespace App\DataFixtures;

use App\Entity\Documental\DocConfiguracion;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenEstadoCivil;
use App\Entity\General\GenFormaPago;
use App\Entity\General\GenSexo;
use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvDocumentoTipo;
use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use App\Entity\RecursoHumano\RhuContratoMotivo;
use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuEntidadTipo;
use App\Entity\RecursoHumano\RhuPension;
use App\Entity\RecursoHumano\RhuSalud;
use App\Entity\RecursoHumano\RhuSubtipoCotizante;
use App\Entity\RecursoHumano\RhuTiempo;
use App\Entity\RecursoHumano\RhuTipoCotizante;
use App\Entity\Transporte\TteConfiguracion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class CargaInicial extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('ENT');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('ENT');
            $arDocumentoTipo->setNombre('ENTRADA DE ALMACEN');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('SAL');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('SAL');
            $arDocumentoTipo->setNombre('SALIDA DE ALMACEN');
            $manager->persist($arDocumentoTipo);
        }
        $arDocumentoTipo = $manager->getRepository('App:Inventario\InvDocumentoTipo')->find('FAC');
        if(!$arDocumentoTipo){
            $arDocumentoTipo = new InvDocumentoTipo();
            $arDocumentoTipo->setCodigoDocumentoTipoPk('FAC');
            $arDocumentoTipo->setNombre('FACTURA');
            $manager->persist($arDocumentoTipo);
        }
        $arInvConfiguracion = $manager->getRepository('App:Inventario\InvConfiguracion')->find(1);
        if(!$arInvConfiguracion){
            $arInvConfiguracion = new InvConfiguracion();
            $arInvConfiguracion->setCodigoConfiguracionPk(1);
            $arInvConfiguracion->setCodigoFormatoMovimiento(1);
            $manager->persist($arInvConfiguracion);
        }
        $arTteConfiguracion= $manager->getRepository('App:Transporte\TteConfiguracion')->find(1);
        if(!$arTteConfiguracion){
            $arTteConfiguracion = new TteConfiguracion();
            $arTteConfiguracion->setCodigoConfiguracionPk(1);
            $arTteConfiguracion->setUsuarioRndc('PENDIENTE');
            $arTteConfiguracion->setEmpresaRndc('PENDIENTE');
            $arTteConfiguracion->setNumeroPoliza(1);
            $arTteConfiguracion->setNumeroIdentificacionAseguradora(1);
            $manager->persist($arTteConfiguracion);
        }
        $arGenConfiguracion= $manager->getRepository('App:General\GenConfiguracion')->find(1);
        if(!$arGenConfiguracion){
            $arGenConfiguracion = new GenConfiguracion();
            $arGenConfiguracion->setCodigoConfiguracionPk(1);
            $arGenConfiguracion->setNit(1);
            $arGenConfiguracion->setDigitoVerificacion(1);
            $arGenConfiguracion->setNombre('PENDIENTE');
            $arGenConfiguracion->setTelefono(1);
            $arGenConfiguracion->setDireccion('PENDIENTE');
            $arGenConfiguracion->setRutaTemporal('PENDIENTE');
            $manager->persist($arGenConfiguracion);
        }
        $arSexo = $manager->getRepository(GenSexo::class)->find('M');
        if(!$arSexo){
            $arSexo = new GenSexo();
            $arSexo->setCodigoSexoPk('M');
            $arSexo->setNombre('MASCULINO');
            $manager->persist($arSexo);
        }
        $arSexo = $manager->getRepository(GenSexo::class)->find('F');
        if(!$arSexo){
            $arSexo = new GenSexo();
            $arSexo->setCodigoSexoPk('F');
            $arSexo->setNombre('FEMENINO');
            $manager->persist($arSexo);
        }
        $arEstadoCivil = $manager->getRepository(GenEstadoCivil::class)->find('C');
        if(!$arEstadoCivil){
            $arEstadoCivil = new GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('C');
            $arEstadoCivil->setNombre('CASADO');
            $manager->persist($arEstadoCivil);
        }
        $arEstadoCivil = $manager->getRepository(GenEstadoCivil::class)->find('D');
        if(!$arEstadoCivil){
            $arEstadoCivil = new GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('D');
            $arEstadoCivil->setNombre('DIVORSIADO');
            $manager->persist($arEstadoCivil);
        }
        $arEstadoCivil = $manager->getRepository(GenEstadoCivil::class)->find('S');
        if(!$arEstadoCivil){
            $arEstadoCivil = new GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('S');
            $arEstadoCivil->setNombre('SOLTERO');
            $manager->persist($arEstadoCivil);
        }
        $arEstadoCivil = $manager->getRepository(GenEstadoCivil::class)->find('U');
        if(!$arEstadoCivil){
            $arEstadoCivil = new GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('U');
            $arEstadoCivil->setNombre('UNIÓN LIBRE');
            $manager->persist($arEstadoCivil);
        }
        $arEstadoCivil = $manager->getRepository(GenEstadoCivil::class)->find('V');
        if(!$arEstadoCivil){
            $arEstadoCivil = new GenEstadoCivil();
            $arEstadoCivil->setCodigoEstadoCivilPk('V');
            $arEstadoCivil->setNombre('VIUDO');
            $manager->persist($arEstadoCivil);
        }

        $arDocConfiguracion= $manager->getRepository('App:Documental\DocConfiguracion')->find(1);
        if(!$arDocConfiguracion){
            $arDocConfiguracion = new DocConfiguracion();
            $arDocConfiguracion->setCodigoConfiguracionPk(1);
            $arDocConfiguracion->setRutaBandeja('PENDIENTE');
            $arDocConfiguracion->setRutaAlmacenamiento('PENDIENTE');
            $manager->persist($arDocConfiguracion);
        }
        $arGenFormaPago = $manager->getRepository('App:General\GenFormaPago')->find('CON');
        if(!$arGenFormaPago){
            $arGenFormaPago = new GenFormaPago();
            $arGenFormaPago->setCodigoFormaPagoPk('CON');
            $arGenFormaPago->setNombre('CONTADO');
            $manager->persist($arGenFormaPago);
        }
        $arGenFormaPago = $manager->getRepository('App:General\GenFormaPago')->find('CRE');
        if(!$arGenFormaPago){
            $arGenFormaPago = new GenFormaPago();
            $arGenFormaPago->setCodigoFormaPagoPk('CRE');
            $arGenFormaPago->setNombre('CREDITO');
            $manager->persist($arGenFormaPago);
        }

        //Carga Inicial Recurso Humano

        //--------------------------- Tiempo -------------------------------------------------
        $arTiempo = $manager->getRepository(RhuTiempo::class)->find('TCOMP');
        if(!$arTiempo){
            $arTiempo = new RhuTiempo();
            $arTiempo->setCodigoTiempoPk('TCOMP');
            $arTiempo->setNombre('TIEMPO COMPLETO');
            $arTiempo->setAbreviatura('C');
            $manager->persist($arTiempo);
        }
        $arTiempo = $manager->getRepository(RhuTiempo::class)->find('TMED');
        if(!$arTiempo){
            $arTiempo = new RhuTiempo();
            $arTiempo->setCodigoTiempoPk('TMED');
            $arTiempo->setNombre('MEDIO TIEMPO');
            $arTiempo->setAbreviatura('M');
            $manager->persist($arTiempo);
        }
        $arTiempo = $manager->getRepository(RhuTiempo::class)->find('TSAB');
        if(!$arTiempo){
            $arTiempo = new RhuTiempo();
            $arTiempo->setCodigoTiempoPk('TSAB');
            $arTiempo->setNombre('SABATINO');
            $arTiempo->setAbreviatura('S');
            $manager->persist($arTiempo);
        }

        //--------------------------- Tipo cotizante -----------------------------------------
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('1');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('1');
            $arTipoCotizante->setNombre('Dependiente');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('2');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('2');
            $arTipoCotizante->setNombre('Servicio Doméstico');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('3');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('3');
            $arTipoCotizante->setNombre('Independiente');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('4');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('4');
            $arTipoCotizante->setNombre('Madre Comunitaria');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('12');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('12');
            $arTipoCotizante->setNombre('Aprendiz SENA en etapa lectiva');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('15');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('15');
            $arTipoCotizante->setNombre('Desempleado con subsidio de caja de compensación familiar');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('16');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('16');
            $arTipoCotizante->setNombre('Independiente agremiado o asociado');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('18');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('18');
            $arTipoCotizante->setNombre('Funcionarios públicos sin tope máximo en el IBC');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('19');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('19');
            $arTipoCotizante->setNombre('Aprendices SENA en etapa productiva');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('20');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('20');
            $arTipoCotizante->setNombre('Estudiantes (Régimen especial-Ley 789/2002)');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('21');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('21');
            $arTipoCotizante->setNombre('Estudiantes de postgrado en salud (Decreto 190 de 1996)');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('22');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('22');
            $arTipoCotizante->setNombre('Profesor de establecimiento particular');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('23');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('23');
            $arTipoCotizante->setNombre('Estudiantes (Decreto 055 de 2015)');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('30');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('30');
            $arTipoCotizante->setNombre('Dependiente entidades o universidades públicas con régimen especial en Salud');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('31');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('31');
            $arTipoCotizante->setNombre('Cooperados o precooperativas de trabajo asociado');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('32');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('32');
            $arTipoCotizante->setNombre('Cotizante miembro de la carrera diplomática o consular de un país extranjero o funcionario de organismo multilateral no sometido a la legislación c');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('33');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('33');
            $arTipoCotizante->setNombre('Beneficiario del fondo de solidaridad pensional');
            $manager->persist($arTipoCotizante);
        }
        $arTipoCotizante = $manager->getRepository(RhuTipoCotizante::class)->find('34');
        if(!$arTipoCotizante){
            $arTipoCotizante = new RhuTipoCotizante();
            $arTipoCotizante->setCodigoTipoCotizantePk('34');
            $arTipoCotizante->setNombre('Concejal amparado por póliza de salud');
            $manager->persist($arTipoCotizante);
        }

        //--------------------------- Subtipo cotizante --------------------------------------
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('0');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('0');
            $arSubtipoCotizante->setNombre('SIN PENSIONAR');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('1');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('1');
            $arSubtipoCotizante->setNombre('Dependiente pensionado por vejez activo (SI no es pensionado es = a 00)');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('2');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('2');
            $arSubtipoCotizante->setNombre('Independiente pensionado por vejez activo');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('3');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('3');
            $arSubtipoCotizante->setNombre('Cotizante no obligado a cotización a pensiones por edad');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('4');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('4');
            $arSubtipoCotizante->setNombre('Cotizante con requisitos cumplidos para pensión.');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('5');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('5');
            $arSubtipoCotizante->setNombre('Cotizante a quien se le ha reconocido indemnización sustitutiva o devolución de saldos');
            $manager->persist($arSubtipoCotizante);
        }
        $arSubtipoCotizante = $manager->getRepository(RhuSubtipoCotizante::class)->find('6');
        if(!$arSubtipoCotizante){
            $arSubtipoCotizante = new RhuSubtipoCotizante();
            $arSubtipoCotizante->setCodigoSubtipoCotizantePk('6');
            $arSubtipoCotizante->setNombre('Cotizante perteneciente a un régimen exceptuado de pensiones o a entidades autorizadas para recibir aportes exclusivamente de un grupo de sus propio');
            $manager->persist($arSubtipoCotizante);
        }

        //--------------------------- Contrato tipo ------------------------------------------
        $arContratoTipo = $manager->getRepository(RhuContratoTipo::class)->find('OBR');
        if(!$arContratoTipo){
            $arContratoTipo = new RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('OBR');
            $arContratoTipo->setNombre('CONTRATO POR OBRA O LABOR CONTRATADA');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(RhuContratoTipo::class)->find('FIJ');
        if(!$arContratoTipo){
            $arContratoTipo = new RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('FIJ');
            $arContratoTipo->setNombre('CONTRATO INDIVIDUAL DE TRABAJO A TERMINO FIJO INFERIOR A UN ANIO');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(RhuContratoTipo::class)->find('IND');
        if(!$arContratoTipo){
            $arContratoTipo = new RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('IND');
            $arContratoTipo->setNombre('CONTRATO INDEFINIDO');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(RhuContratoTipo::class)->find('APR');
        if(!$arContratoTipo){
            $arContratoTipo = new RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('APR');
            $arContratoTipo->setNombre('CONTRATO POR APRENDIZ DEL SENA');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(RhuContratoTipo::class)->find('PRA');
        if(!$arContratoTipo){
            $arContratoTipo = new RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('PRA');
            $arContratoTipo->setNombre('CONTRATO DE PRACTICA ESTUDIANTIL');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }
        $arContratoTipo = $manager->getRepository(RhuContratoTipo::class)->find('PRE');
        if(!$arContratoTipo){
            $arContratoTipo = new RhuContratoTipo();
            $arContratoTipo->setCodigoContratoTipoPk('PRE');
            $arContratoTipo->setNombre('CONTRATO POR PRESTACIÓN DE SERVICIO');
            $arContratoTipo->setIndefinido(false);
            $manager->persist($arContratoTipo);
        }

        //--------------------------- Contrato motivo ----------------------------------------
        $arContratoMotivo = $manager->getRepository(RhuContratoMotivo::class)->find('TCO');
        if(!$arContratoMotivo){
            $arContratoMotivo = new RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('TC');
            $arContratoMotivo->setMotivo('TERMINACIÓN DE CONTRATO');
        }
        $arContratoMotivo = $manager->getRepository(RhuContratoMotivo::class)->find('RVO');
        if(!$arContratoMotivo){
            $arContratoMotivo = new RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('RV');
            $arContratoMotivo->setMotivo('RENUNCIA VOLUNTARIA');
        }
        $arContratoMotivo = $manager->getRepository(RhuContratoMotivo::class)->find('MAC');
        if(!$arContratoMotivo){
            $arContratoMotivo = new RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('MA');
            $arContratoMotivo->setMotivo('MUTUO ACUERDO');
        }
        $arContratoMotivo = $manager->getRepository(RhuContratoMotivo::class)->find('SJC');
        if(!$arContratoMotivo){
            $arContratoMotivo = new RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('SJC');
            $arContratoMotivo->setMotivo('SIN JUSTA CAUSA');
        }
        $arContratoMotivo = $manager->getRepository(RhuContratoMotivo::class)->find('CJC');
        if(!$arContratoMotivo){
            $arContratoMotivo = new RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('CJC');
            $arContratoMotivo->setMotivo('CON JUSTA CAUSA');
        }
        $arContratoMotivo = $manager->getRepository(RhuContratoMotivo::class)->find('JUB');
        if(!$arContratoMotivo){
            $arContratoMotivo = new RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('J');
            $arContratoMotivo->setMotivo('JUBILACIÓN');
        }
        $arContratoMotivo = $manager->getRepository(RhuContratoMotivo::class)->find('MUE');
        if(!$arContratoMotivo){
            $arContratoMotivo = new RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('M');
            $arContratoMotivo->setMotivo('MUERTE');
        }
        $arContratoMotivo = $manager->getRepository(RhuContratoMotivo::class)->find('INA');
        if(!$arContratoMotivo){
            $arContratoMotivo = new RhuContratoMotivo();
            $arContratoMotivo->setCodigoContratoMotivoPk('INA');
            $arContratoMotivo->setMotivo('INACTIVO POR EL USUARIO');
        }

        //--------------------------- Clasificación de riesgo --------------------------------
        $arClasificacionRiesgo = $manager->getRepository(RhuClasificacionRiesgo::class)->find('I');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('I');
            $arClasificacionRiesgo->setNombre('I - 0.522');
            $arClasificacionRiesgo->setPorcentaje(0.522);
            $manager->persist($arClasificacionRiesgo);
        }
        $arClasificacionRiesgo = $manager->getRepository(RhuClasificacionRiesgo::class)->find('II');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('II');
            $arClasificacionRiesgo->setNombre('II - 1.044');
            $arClasificacionRiesgo->setPorcentaje(1.044);
            $manager->persist($arClasificacionRiesgo);
        }
        $arClasificacionRiesgo = $manager->getRepository(RhuClasificacionRiesgo::class)->find('III');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('III');
            $arClasificacionRiesgo->setNombre('III - 2.436');
            $arClasificacionRiesgo->setPorcentaje(2.436);
            $manager->persist($arClasificacionRiesgo);
        }
        $arClasificacionRiesgo = $manager->getRepository(RhuClasificacionRiesgo::class)->find('IV');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('IV');
            $arClasificacionRiesgo->setNombre('IV - 4.350');
            $arClasificacionRiesgo->setPorcentaje(4.35);
            $manager->persist($arClasificacionRiesgo);
        }
        $arClasificacionRiesgo = $manager->getRepository(RhuClasificacionRiesgo::class)->find('V');
        if(!$arClasificacionRiesgo){
            $arClasificacionRiesgo = new RhuClasificacionRiesgo();
            $arClasificacionRiesgo->setCodigoClasificacionRiesgoPk('V');
            $arClasificacionRiesgo->setNombre('V - 6.960');
            $arClasificacionRiesgo->setPorcentaje(6.96);
            $manager->persist($arClasificacionRiesgo);
        }

        //--------------------------------------- Salud --------------------------------------
        $arSalud = $manager->getRepository(RhuSalud::class)->find('EMP');
        if(!$arSalud){
            $arSalud = new RhuSalud();
            $arSalud->setCodigoSaludPk('EMP');
            $arSalud->setNombre('EMPLEADO');
            $arSalud->setPorcentajeEmpleado(4);
            $arSalud->setPorcentajeEmpleador(8.5);
            $manager->persist($arSalud);
        }
        $arSalud = $manager->getRepository(RhuSalud::class)->find('EMR');
        if(!$arSalud){
            $arSalud = new RhuSalud();
            $arSalud->setCodigoSaludPk('EMR');
            $arSalud->setNombre('EMPLEADOR');
            $arSalud->setPorcentajeEmpleado(0);
            $arSalud->setPorcentajeEmpleador(12.5);
            $manager->persist($arSalud);
        }

        //--------------------------------------- Pension ------------------------------------
        $arPension = $manager->getRepository(RhuPension::class)->find('NOR');
        if(!$arPension){
            $arPension = new RhuPension();
            $arPension->setCodigoPensionPk('NOR');
            $arPension->setNombre('NORMAL');
            $arPension->setPorcentajeEmpleado(4);
            $arPension->setPorcentajeEmpleador(12);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(RhuPension::class)->find('ALT');
        if(!$arPension){
            $arPension = new RhuPension();
            $arPension->setCodigoPensionPk('ALT');
            $arPension->setNombre('ALTO RIESGO');
            $arPension->setPorcentajeEmpleado(4);
            $arPension->setPorcentajeEmpleador(22);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(RhuPension::class)->find('EMN');
        if(!$arPension){
            $arPension = new RhuPension();
            $arPension->setCodigoPensionPk('EMN');
            $arPension->setNombre('EMPLEADOR NORMAL');
            $arPension->setPorcentajeEmpleado(0);
            $arPension->setPorcentajeEmpleador(16);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(RhuPension::class)->find('EMA');
        if(!$arPension){
            $arPension = new RhuPension();
            $arPension->setCodigoPensionPk('EMA');
            $arPension->setNombre('EMPLEADOR ALTO RIESGO');
            $arPension->setPorcentajeEmpleado(0);
            $arPension->setPorcentajeEmpleador(26);
            $manager->persist($arPension);
        }
        $arPension = $manager->getRepository(RhuPension::class)->find('PEN');
        if(!$arPension){
            $arPension = new RhuPension();
            $arPension->setCodigoPensionPk('PEN');
            $arPension->setNombre('PENSIONADO');
            $arPension->setPorcentajeEmpleado(0);
            $arPension->setPorcentajeEmpleador(0);
            $manager->persist($arPension);
        }
        $manager->flush();
    }
}
