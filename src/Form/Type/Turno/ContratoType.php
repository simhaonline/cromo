<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurContrato;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaGeneracion')
            ->add('soporte')
            ->add('codigoClienteFk')
            ->add('codigoContratoTipoFk')
            ->add('codigoSectorFk')
            ->add('cantidad')
            ->add('estrato')
            ->add('horas')
            ->add('horasDiurnas')
            ->add('horasNocturnas')
            ->add('vrTotalCosto')
            ->add('vrTotalContrato')
            ->add('vrTotalPrecioAjustado')
            ->add('vrTotalPrecioMinimo')
            ->add('vrSubtotal')
            ->add('vrIva')
            ->add('vrBaseAiu')
            ->add('vrSalarioBase')
            ->add('vrTotal')
            ->add('usuario')
            ->add('comentarios')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurContrato::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoContratoPk",      "tipo":"pk"      ,"ayuda":"Codigo del registro"                      ,"titulo":"ID"},
            {"campo":"fechaGeneracion",       "tipo":"datetime","ayuda":"Fecha del ultimo registro generado"       ,"titulo":"FECHA_GENERACION"},
            {"campo":"clienteRel.nombreCorto","tipo":"texto"   ,"ayuda":"Cliente del contrato"                     ,"titulo":"CLIENTE", "relacion":""},
            {"campo":"contratoTipoRel.nombre","tipo":"texto"   ,"ayuda":"Tipo de contrato"                         ,"titulo":"CONTRATO_TIPO", "relacion":""},
            {"campo":"sectorRel.nombre",      "tipo":"texto"   ,"ayuda":"Sector al que pertenece el cliente"       ,"titulo":"SECTOR", "relacion":""},
            {"campo":"soporte",               "tipo":"texto"   ,"ayuda":"Soporte del registro"                     ,"titulo":"SOPORTE"}
        ]';
        return $campos;
    }
}
