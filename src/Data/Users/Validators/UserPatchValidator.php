<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Users\Validators;

use CarloNicora\Minimalism\Services\DataValidator\Abstracts\AbstractDataValidator;
use CarloNicora\Minimalism\Services\DataValidator\Enums\DataTypes;
use CarloNicora\Minimalism\Services\DataValidator\Objects\AttributeValidator;
use CarloNicora\Minimalism\Services\DataValidator\Objects\DocumentValidator;
use CarloNicora\Minimalism\Services\DataValidator\Objects\ResourceValidator;
use CarloNicora\Minimalism\Services\Users\Data\Dictionary\UsersDictionary;

class UserPatchValidator extends AbstractDataValidator
{
    /**
     *
     */
    public function __construct(
    )
    {
        $this->documentValidator = new DocumentValidator();

        $resourceValidator = new ResourceValidator(
            type: UsersDictionary::User->value,
            isIdRequired: true,
            isSingleResource: true,
        );

        $resourceValidator->addAttributeValidator(new AttributeValidator(name: 'email'));
        $resourceValidator->addAttributeValidator(new AttributeValidator(name: 'username'));
        $resourceValidator->addAttributeValidator(new AttributeValidator(name: 'password'));
        $resourceValidator->addAttributeValidator(new AttributeValidator(name: 'meta',type: DataTypes::array));

        $this->documentValidator->addResourceValidator(
            validator: $resourceValidator
        );
    }
}