<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Abstracts;

use CarloNicora\Minimalism\Factories\ObjectFactory;
use CarloNicora\Minimalism\Interfaces\Cache\Interfaces\CacheInterface;
use CarloNicora\Minimalism\Interfaces\Security\Interfaces\SecurityInterface;
use CarloNicora\Minimalism\Interfaces\SimpleObjectInterface;
use CarloNicora\Minimalism\Services\ResourceBuilder\ResourceBuilder;

class AbstractUserResourceFactory implements SimpleObjectInterface
{
    /**
     * @param ObjectFactory $objectFactory
     * @param ResourceBuilder $builder
     * @param SecurityInterface $authorisation
     * @param CacheInterface|null $cache
     */
    public function __construct(
        protected ObjectFactory $objectFactory,
        protected ResourceBuilder $builder,
        protected SecurityInterface $authorisation,
        protected ?CacheInterface $cache=null,
    )
    {
    }
}