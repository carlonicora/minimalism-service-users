<?php
namespace CarloNicora\Minimalism\Services\Users\Models\Abstracts;

use CarloNicora\Minimalism\Abstracts\AbstractModel;
use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Factories\MinimalismFactories;
use CarloNicora\Minimalism\Interfaces\Security\Interfaces\SecurityInterface;

abstract class AbstractUsersModel extends AbstractModel
{
    /** @var SecurityInterface  */
    protected SecurityInterface $authorisation;

    /**
     * @param MinimalismFactories $minimalismFactories
     * @param string|null $function
     * @throws MinimalismException
     */
    public function __construct(
        MinimalismFactories $minimalismFactories,
        ?string $function = null,
    )
    {
        parent::__construct($minimalismFactories,$function);

        $this->authorisation = $minimalismFactories->getServiceFactory()->create(SecurityInterface::class);

        if (!$this->authorisation->getUserId()){
            throw new MinimalismException(status: HttpCode::Unauthorized, message: 'Unauthorised');
        }
    }

}