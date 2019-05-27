<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurContrato;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('contratoTipoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurContratoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.codigoContratoTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo contrato:'
            ])

            ->add('sectorRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurSector',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:'
            ])
            ->add('vrSalarioBase', NumberType::class)
            ->add('estrato', TextType::class)
            ->add('comentarios', TextareaType::class,['required'=>false])
            ->add('guardar', SubmitType::class);
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
            {"campo":"contratoTipoRel.nombre","tipo":"texto"   ,"ayuda":"Tipo de contrato"                         ,"titulo":"TIPO", "relacion":""},            
            {"campo":"clienteRel.numeroIdentificacion","tipo":"texto"   ,"ayuda":"Numero de identificacion"                     ,"titulo":"NIT", "relacion":""},
            {"campo":"clienteRel.nombreCorto" ,"tipo":"texto"   ,"ayuda":"Cliente del contrato"                     ,"titulo":"CLIENTE", "relacion":""},
            {"campo":"sectorRel.nombre",      "tipo":"texto"   ,"ayuda":"Sector al que pertenece el cliente"       ,"titulo":"SECTOR", "relacion":""},
            {"campo":"horas",               "tipo":"texto"   ,"ayuda":"Contrato horas"                     ,"titulo":"H"},
            {"campo":"horasDiurnas",               "tipo":"texto"   ,"ayuda":"Contrato horas diurnas"                     ,"titulo":"H:D"},
            {"campo":"horasNocturnas",               "tipo":"texto"   ,"ayuda":"Contrato horas nocturnas"                     ,"titulo":"H:N"},
            {"campo":"vrTotal",               "tipo":"texto"   ,"ayuda":"Total del contrato"                     ,"titulo":"VALOR"},
            {"campo":"estadoAutorizado",               "tipo":"texto"   ,"ayuda":"Autoriza contrato"                     ,"titulo":"AUT"},
            {"campo":"estadoCerrado",               "tipo":"texto"   ,"ayuda":"Contrato cerrado"                     ,"titulo":"CER"}
            
            
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoContratoPk",     "tipo":"TextType",  "propiedades":{"label":"Codigo"}},            
            {"child":"estadoAutorizado",   "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoCerrado",      "tipo":"ChoiceType","propiedades":{"label":"Cerrado",       "choices":{"SIN CERRAR":true,"CERRADO":false}}}
        ]';
        return $campos;
    }
}
