<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvSubgrupo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubgrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSubgrupoPk',TextType::class,['label' => 'Código subgrupo: '])
            ->add('grupoRel',EntityType::class,[
                'class' => 'App\Entity\Inventario\InvGrupo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.nombre','DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Grupo:'
            ])
            ->add('lineaRel',EntityType::class,[
                'class' => 'App\Entity\Inventario\InvLinea',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.nombre','DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Linea:'
            ])
            ->add('nombre',TextType::class,['label' => 'Nombre: '])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvSubgrupo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoSubgrupoPk",      "tipo":"pk"     ,"ayuda":"Codigo del registro",        "titulo":"ID"},
            {"campo":"grupoRel.nombre",       "tipo":"texto"  ,"ayuda":"Nombre del grupo",           "titulo":"GRUPO", "relacion":""},
            {"campo":"lineaRel.nombre",       "tipo":"texto"  ,"ayuda":"Nombre de la linea",         "titulo":"LINEA", "relacion":""},
            {"campo":"nombre",                "tipo":"texto"  ,"ayuda":"Nombre del grupo",           "titulo":"NOMBRE"}                     
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoSubgrupoPk",      "tipo":"pk"     ,"ayuda":"Codigo del registro",        "titulo":"ID"},
            {"campo":"grupoRel.nombre",       "tipo":"texto"  ,"ayuda":"Nombre del grupo",           "titulo":"GRUPO", "relacion":""},
            {"campo":"lineaRel.nombre",       "tipo":"texto"  ,"ayuda":"Nombre de la linea",         "titulo":"LINEA", "relacion":""},
            {"campo":"nombre",                "tipo":"texto"  ,"ayuda":"Nombre del grupo",           "titulo":"NOMBRE"}                                            
        ]';
        return $campos;
    }
}
