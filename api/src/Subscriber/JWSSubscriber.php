<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\SerializerInterface;

class JWSSubscriber implements EventSubscriberInterface
{
    private $params;
    private $serializer;
    private $propertyAccessor;
    private $em;
    private $commonGroundService;

    public function __construct(ParameterBagInterface $params, SerializerInterface $serializer, EntityManagerInterface $em, CommonGroundService $commonGroundService)
    {
        $this->params = $params;
        $this->serializer = $serializer;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->em = $em;
        $this->commonGroundService = $commonGroundService;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['dumpJWS', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function dumpJWS(ViewEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $contentType = $event->getRequest()->headers->get('accept');
        $route = $event->getRequest()->attributes->get('_route');
        $application = $event->getControllerResult();


        // We should also check on entity = component
        if ($method != 'GET' || (!strpos($route, '_jwt_token'))) {
            return;
        }

        echo $this->commonGroundService->getJwtToken($application);

        die;
    }
}
