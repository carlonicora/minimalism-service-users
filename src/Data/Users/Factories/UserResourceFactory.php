<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Users\Factories;

use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Services\Users\Data\Abstracts\AbstractUserResourceFactory;
use CarloNicora\Minimalism\Services\Users\Data\Cache\UsersCacheFactory;
use CarloNicora\Minimalism\Services\Users\Data\Users\Builders\PrivateUserBuilder;
use CarloNicora\Minimalism\Services\Users\Data\Users\Builders\UserBuilder;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;
use CarloNicora\Minimalism\Services\Users\Data\Users\IO\UserIO;
use Exception;

class UserResourceFactory extends AbstractUserResourceFactory
{
    /**
     * @param int $userId
     * @return ResourceObject
     * @throws Exception
     */
    public function byId(
        int $userId,
    ): ResourceObject
    {
        /** @var User $data */
        $data = $this->objectFactory->create(UserIO::class)->readById($userId);

        $isPrivateData = $data->getId() === $this->authorisation->getUserId();

        return $this->builder->buildResource(
            builderClass: $isPrivateData ? PrivateUserBuilder::class : UserBuilder::class,
            data: $data,
            cacheBuilder: $isPrivateData ? UsersCacheFactory::privateUser($userId) : UsersCacheFactory::user($userId),
        );
    }
}