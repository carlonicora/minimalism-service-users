<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Users\Factories;

use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Services\Users\Data\Abstracts\AbstractUserResourceFactory;
use CarloNicora\Minimalism\Services\Users\Data\Cache\UsersCacheFactory;
use CarloNicora\Minimalism\Services\Users\Data\Users\Builders\PrivateUserBuilder;
use CarloNicora\Minimalism\Services\Users\Data\Users\Builders\UserBuilder;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;
use Exception;

class UserResourceFactory extends AbstractUserResourceFactory
{
    /**
     * @param User $user
     * @return ResourceObject
     * @throws Exception
     */
    public function byData(
        User $user,
    ): ResourceObject
    {
        $isPrivateData = $user->getId() === $this->authorisation->getUserId();

        return $this->builder->buildResource(
            builderClass: $isPrivateData ? PrivateUserBuilder::class : UserBuilder::class,
            data: $user,
            cacheBuilder: $isPrivateData ? UsersCacheFactory::privateUser($user->getId()) : UsersCacheFactory::user($user->getId()),
        );
    }
}