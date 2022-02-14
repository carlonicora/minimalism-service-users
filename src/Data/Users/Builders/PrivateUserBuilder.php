<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Users\Builders;

use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\Minimalism\Services\ResourceBuilder\Interfaces\ResourceableDataInterface;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;
use Exception;

class PrivateUserBuilder extends UserBuilder
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
        $response = parent::buildResource($data);

        $response->attributes->add(name: 'email', value: $data->getEmail());

        $response->meta->add(
            name: 'dates',
            value: [
                'created' => date('Y-m-d H:i:s', $data->getCreatedAt()),
                'updated' => date('Y-m-d H:i:s', $data->getUpdatedAt()),
            ]
        );

        return $response;
    }
}