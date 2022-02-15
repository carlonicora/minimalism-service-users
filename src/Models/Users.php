<?php
namespace CarloNicora\Minimalism\Services\Users\Models;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Interfaces\Encrypter\Interfaces\EncrypterInterface;
use CarloNicora\Minimalism\Interfaces\Encrypter\Parameters\PositionedEncryptedParameter;
use CarloNicora\Minimalism\Parameters\PositionedParameter;
use CarloNicora\Minimalism\Services\Users\Data\Users\Factories\UserResourceFactory;
use CarloNicora\Minimalism\Services\Users\Data\Users\IO\UserIO;
use CarloNicora\Minimalism\Services\Users\Models\Abstracts\AbstractUsersModel;
use Exception;

class Users extends AbstractUsersModel
{
    /**
     * @param EncrypterInterface $encrypter
     * @param PositionedParameter $user
     * @return HttpCode
     * @throws MinimalismException
     * @throws Exception
     */
    public function get(
        EncrypterInterface $encrypter,
        PositionedParameter $user,
    ): HttpCode
    {
        $userData = null;
        $userId = null;

        if (strtolower($user->getValue()) === 'me') {
            $userId = $this->authorisation->getUserId();
        } elseif (str_starts_with($user->getValue(), '@')){
            $userData = $this->objectFactory->create(UserIO::class)->readByUsername(substr($user->getValue(), 1));
        } else {
            $userId = $encrypter->decryptId($user->getValue());
        }

        if ($userData === null && $userId !== null) {
            $userData = $this->objectFactory->create(UserIO::class)->readById(
                userId: $userId,
            );
        }

        $this->document->addResource(
            resource: $this->objectFactory->create(UserResourceFactory::class)->byData($userData),
        );

        return HttpCode::Ok;
    }

    /**
     * @param PositionedEncryptedParameter $userId
     * @return HttpCode
     */
    public function patch(
        PositionedEncryptedParameter $userId,
    ): HttpCode
    {
        return HttpCode::Ok;
    }

    /**
     * @param PositionedEncryptedParameter $userId
     * @return HttpCode
     * @throws MinimalismException
     * @throws Exception
     */
    public function delete(
        PositionedEncryptedParameter $userId,
    ): HttpCode
    {
        $user = $this->objectFactory->create(UserIO::class)->readById($userId->getValue());

        if ($user->getId() !== $this->authorisation->getUserId()){
            throw new MinimalismException(status: HttpCode::Forbidden, message: 'Forbidden');
        }

        $this->objectFactory->create(UserIO::class)->delete($user);

        return HttpCode::NoContent;
    }
}