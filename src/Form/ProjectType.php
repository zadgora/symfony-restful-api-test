<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProjectType extends AbstractType implements EventSubscriberInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255, 'min' => 3]),

                ],
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new Length(['max' => 255, 'min' => 3]),
                ],
            ])
            ->add('status', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255, 'min' => 3]),

                ],
            ])
            ->add('duration', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255, 'min' => 3]),

                ],
            ])
            ->add('client', TextType::class, [
                'constraints' => [
                    new Length(['max' => 255, 'min' => 3]),

                ],
            ])
            ->add('company', TextType::class, [
                'constraints' => [
                    new Length(['max' => 255, 'min' => 3]),

                ],
            ]);
        $builder->addEventSubscriber($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'ensureOneFieldIsSubmitted',
        ];
    }

    public function ensureOneFieldIsSubmitted(FormEvent $event)
    {
        $project = $event->getData(); 
        $client = $project->getClient();
        $company = $project->getCompany();

        if (!isset($client) && !isset($company)) {
            
            throw new TransformationFailedException(
                '"company" or "client" field must be set',
                0,
                null,
                'Company or Client field must be set',
                []
            );
        }
    }
}
