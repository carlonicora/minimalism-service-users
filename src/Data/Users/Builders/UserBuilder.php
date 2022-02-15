<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Users\Builders;

use CarloNicora\JsonApi\Objects\Link;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Services\ResourceBuilder\Interfaces\ResourceableDataInterface;
use CarloNicora\Minimalism\Services\Users\Data\Abstracts\AbstractUsersBuilder;
use CarloNicora\Minimalism\Services\Users\Data\Dictionary\UsersDictionary;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;
use Exception;

class UserBuilder extends AbstractUsersBuilder
{
    /**
     * @param User $data
     * @return ResourceObject
     * @throws Exception
     */
    public function buildResource(
        ResourceableDataInterface $data
    ): ResourceObject
    {
        $response = new ResourceObject(
            type: UsersDictionary::User->value,
            id: $this->encrypter?->encryptId($data->getId()),
        );

        $response->attributes->add(name: 'username', value: $data->getUsername());
        $response->attributes->add(name: 'avatar', value: $data->getAvatar());

        $response->links->add(
            link: new Link(
                name: 'self',
                href: $this->users->getUserUrl($data),
            )
        );

        return $response;
    }
}