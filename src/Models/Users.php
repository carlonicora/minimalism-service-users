<?php
namespace CarloNicora\Minimalism\Services\Users\Models;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Interfaces\Encrypter\Interfaces\EncrypterInterface;
use CarloNicora\Minimalism\Interfaces\Encrypter\Parameters\PositionedEncryptedParameter;
use CarloNicora\Minimalism\Parameters\PositionedParameter;
use CarloNicora\Minimalism\Services\Users\Data\Users\Validators\UserPatchValidator;
use CarloNicora\Minimalism\Services\Users\Data\Users\Factories\UserResourceFactory;
use CarloNicora\Minimalism\Services\Users\Data\Users\IO\UserIO;
use CarloNicora\Minimalism\Services\Users\Models\Abstracts\AbstractUsersModel;
use Exception;

class Users extends AbstractUsersModel
{
    /**
     * @param EncrypterInterface $encrypter
     * @param PositionedParameter|null $user
     * @param array|null $userIds
     * @return HttpCode
     * @throws MinimalismException
     * @throws Exception
     */
    public function get(
        EncrypterInterface $encrypter,
        ?PositionedParameter $user=null,
        ?array $userIds=null,
    ): HttpCode
    {
        if ($user === null && $userIds === null){
            throw new MinimalismException(status: HttpCode::PreconditionFailed, message: 'At least one valid user identifier is required');
        }

        if ($user !== null) {
            $userData = null;
            $userId = null;

            if (strtolower($user->getValue()) === 'me') {
                $userId = $this->authorisation->getUserId();
            } elseif (str_starts_with($user->getValue(), '@')) {
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
        } else {
            $users = [];

            foreach ($userIds as $userId){
                $userData = $this->objectFactory->create(UserIO::class)->readById(userId: $encrypter->decryptId($userId));
                $users[] = $this->objectFactory->create(UserResourceFactory::class)->byData($userData);
            }

            $this->document->addResourceList(
                resourceList: $users,
            );
        }

        return HttpCode::Ok;
    }

    /**
     * @param EncrypterInterface $encrypter
     * @param PositionedEncryptedParameter $userId
     * @param UserPatchValidator $payload
     * @return HttpCode
     * @throws MinimalismException|Exception
     */
    public function patch(
        EncrypterInterface           $encrypter,
        PositionedEncryptedParameter $userId,
        UserPatchValidator           $payload,
    ): HttpCode
    {
        $user = $this->objectFactory->create(UserIO::class)->readById($userId->getValue());

        if ($this->authorisation->getUserId() !== $user->getId()) {
            throw new MinimalismException(status: HttpCode::Forbidden, message: 'forbidden');
        }

        if ($user->getId() !== $encrypter->decryptId($payload->getSingleResource()->id)) {
            throw new MinimalismException(status: HttpCode::Conflict, message: 'The passed resource is different from the current user');
        }

        if ($payload->getSingleResource()->attributes->has(name: 'email')){
            $user->setEmail($payload->getSingleResource()->attributes->get(name: 'email'));
        }

        if ($payload->getSingleResource()->attributes->has(name: 'username')){
            $newUsername = $payload->getSingleResource()->attributes->get(name: 'username');

            try {
                $usernameUser = $this->objectFactory->create(UserIO::class)->readByUsername($newUsername);

                if ($usernameUser->getId() === $user->getId()){
                    $usernameUser = null;
                }
            } catch (MinimalismException) {
                $usernameUser = null;
            }

            if ($usernameUser !== null){
                throw new MinimalismException(status: HttpCode::Conflict, message: 'Username already in use');
            }

            $user->setUsername($newUsername);
        }

        if ($payload->getSingleResource()->attributes->has(name: 'password')){
            $user->setPassword(
                password_hash($payload->getSingleResource()->attributes->get(name: 'password'), PASSWORD_BCRYPT),
            );
        }

        if ($payload->getSingleResource()->attributes->has(name: 'meta')){
            foreach ($payload->getSingleResource()->attributes->get(name: 'meta') as $metaId=>$value){
                $user->addMeta(metaId: $metaId, value: $value);
            }
        }

        $this->objectFactory->create(UserIO::class)->update($user);

        return HttpCode::NoContent;
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