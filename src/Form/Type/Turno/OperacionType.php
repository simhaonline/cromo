<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurOperacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['required' => true, 'label'=>'Nombre'])
            ->add('nombreCorto', TextType::class, ['required' => true, 'label'=>'Nombre corto'])
            ->add('clienteRel', EntityType::class, [
                'class' => TurCliente::class,
                'label' => 'Cliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.codigoClientePk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombreCorto',
                'placeholder' => 'TODOS',
            ])
            ->add('guardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurOperacion::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[	
            {"campo":"codigoOperacionPk",           "tipo":"pk",        "ayuda":"Codigo del registro",      "titulo":"ID"},	
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"NOMBRE"},
            {"campo":"nombreCorto",                 "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"NOMBRE CORTO"},
            {"campo":"clienteRel.nombreCorto",      "tipo":"texto",     "ayuda":"CLIENTE",                           "titulo":"TIPO",         "relacion":""}

        ]';
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoOperacionPk", "tipo":"pk",    "ayuda":"Codigo del registro",                    "titulo":"ID"},
            {"campo":"nombre",             "tipo":"texto", "ayuda":"Nombre del registro",                    "titulo":"NOMBRE"},
            {"campo":"nombreCorto",                      "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"NOMBRE CORTO"},
            {"campo":"clienteRel.nombreCorto",      "tipo":"texto",     "ayuda":"CLIENTE",                           "titulo":"TIPO",         "relacion":""}


        ]';
        return $campos;
    }
}
