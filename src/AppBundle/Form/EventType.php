<?php
namespace AppBundle\Form;

use AppBundle\Domain\Model\Event;
use Contentful\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title')
            ->add('date', DateTimeType::class, [
                'html5' => true,
                'years' => range(date('Y'), date('Y') + 5)
            ])
            ->add('location', LocationType::class, [
                'inherit_data' => true,
            ])
            ->add('description', TextareaType::class)
            ->add('tags', TextType::class, [
                'attr' => [
                    'placeholder' => 'SFPot, Nantes, Pizza'
                ]
            ])
            ->add('link')
        ;

        $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString) {
                    return explode(', ', $tagsAsString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Event::class);
    }
}