<?php

namespace Diamante\DeskBundle\EventListener\Search;

use Diamante\DeskBundle\Entity\Ticket;
use Oro\Bundle\SearchBundle\Engine\ObjectMapper;
use Oro\Bundle\SearchBundle\Event\PrepareResultItemEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PrepareResultItemListener
{
    const TITLE_PART_SEPARATOR = ' - ';

    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var ObjectMapper
     */
    private $mapper;

    /**
     * @param ContainerInterface $container
     * @param ObjectMapper $mapper
     */
    public function __construct(
        ContainerInterface $container,
        ObjectMapper $mapper
    ) {
        $this->container = $container;
        $this->mapper = $mapper;
    }

    /**
     * @param PrepareResultItemEvent $event
     */
    public function process(PrepareResultItemEvent $event)
    {
        $entity = $event->getEntity();

        if (!$entity instanceof Ticket) {
            return;
        }

        $item = $event->getResultItem();
        $name = $item->getEntityName();

        $routeParameters = $this->mapper->getEntityMapParameter($name, 'route');
        $routeData = array();
        foreach ($routeParameters['parameters'] as $parameter => $field) {
            $routeData[$parameter] = $entity->getKey();
            break;
        }

        $router = $this->container->get('router');

        $url = $router->generate(
            $routeParameters['name'],
            $routeData,
            true
        );

        $item->setRecordUrl($url);

        $title = $entity->getKey() . static::TITLE_PART_SEPARATOR . $entity->getSubject();
        $item->setRecordTitle($title);
    }
}