<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Users\Commands;

use CarloNicora\Minimalism\Enums\HttpCode;
use CarloNicora\Minimalism\Exceptions\MinimalismException;
use CarloNicora\Minimalism\Factories\ObjectFactory;
use CarloNicora\Minimalism\Interfaces\SimpleObjectInterface;
use CarloNicora\Minimalism\Interfaces\Sql\Interfaces\SqlInterface;
use CarloNicora\Minimalism\Services\Users\Data\SocialLogins\DataObjects\SocialLogin;
use CarloNicora\Minimalism\Services\Users\Data\SocialLogins\IO\SocialLoginIO;
use CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects\User;
use CarloNicora\Minimalism\Services\Users\Data\Users\IO\UserIO;
use CarloNicora\Minimalism\Services\Users\Interfaces\AuthenticationInterface;
use Exception;

class Authenticator implements AuthenticationInterface, SimpleObjectInterface
{
    /**
     * @param ObjectFactory $objectFactory
     * @param SqlInterface $data
     */
    public function __construct(
        private readonly ObjectFactory $objectFactory,
        private readonly SqlInterface  $data,
    )
    {
    }

    /**
     * @param User $user
     * @return void
     * @throws Exception
     */
    private function analyseSocialLogin(
        User $user,
    ): void
    {
        $user->setIsSocialLogin(
            $this->objectFactory->create(SocialLoginIO::class)->hasSocialLogins($user),
        );
    }

    /**
     * @param string $email
     * @return User
     * @throws Exception
     */
    public function authenticateByEmail(
        string $email
    ): User
    {
        $response = $this->objectFactory->create(UserIO::class)->readByEmail($email);
        $this->analyseSocialLogin($response);

        return $response;
    }

    /**
     * @param int $userId
     * @return User
     * @throws Exception
     */
    public function authenticateById(
        int $userId
    ): User
    {
        $response = $this->objectFactory->create(UserIO::class)->readById($userId);
        $this->analyseSocialLogin($response);

        return $response;
    }

    /**
     * @param string $username
     * @return User
     * @throws Exception
     */
    public function authenticateByUsername(
        string $username,
    ): User
    {
        $response = $this->objectFactory->create(UserIO::class)->readByUsername($username);
        $this->analyseSocialLogin($response);

        return $response;
    }

    /**
     * @param string $email
     * @param string|null $name
     * @param string|null $provider
     * @return User
     * @throws Exception
     */
    public function generateNewUser(
        string $email,
        string $name=null,
        string $provider=null
    ): User
    {
        if (($email = filter_var($email, FILTER_VALIDATE_EMAIL)) === false) {
            throw new MinimalismException(status: HttpCode::PreconditionFailed, message: 'The email address is invalid');
        }

        [$initialUsername,] = explode('@', $email);
        $username = $initialUsername;

        $validUsername=false;
        $usernameAttempts=0;
        do
        {
            try {
                /** @noinspection UnusedFunctionResultInspection */
                $this->objectFactory->create(UserIO::class)->readByUsername($username);
                $usernameAttempts++;
                $username = $initialUsername . '_' . $usernameAttempts;
            } catch (MinimalismException) {
                $validUsername = true;
            }
        } while (!$validUsername);

        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->addMeta(metaId: 'name', value: $name);

        $user = $this->data->create(
            queryFactory: $user,
            responseType: User::class,
        );

        if ($provider !== null){
            $socialLogin = new SocialLogin();
            $socialLogin->setUserId($user->getId());
            $socialLogin->setSocial(strtolower($provider));

            /** @noinspection UnusedFunctionResultInspection */
            $this->data->create(
                queryFactory: $socialLogin,
            );

            $user->setIsSocialLogin(true);
        }

        return $user;
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function activateUser(
        User $user
    ): void
    {
    }

    /**
     * @param int $userId
     * @param string $password
     * @throws Exception
     */
    public function updatePassword(
        int $userId,
        string $password
    ): void
    {
        $user = $this->objectFactory->create(UserIO::class)->readById($userId);

        $user->setPassword($password);

        $this->data->update(
            queryFactory: $user,
        );
    }

    /**
     * @param int $userId
     * @param string $username
     * @return void
     * @throws Exception
     */
    public function updateUsername(
        int $userId,
        string $username,
    ): void
    {
        $userByUsername = null;
        try {
            $userByUsername = $this->objectFactory->create(UserIO::class)->readByUsername($username);
            if ($userByUsername->getId() === $userId){
                $userByUsername = null;
            }
        } catch (Exception) {
        }

        if ($userByUsername !== null){
            throw new MinimalismException(status: HttpCode::Conflict, message: 'Username already in use');
        }

        $user = $this->objectFactory->create(UserIO::class)->readById($userId);
        $user->setUsername($username);

        $this->data->update(
            queryFactory: $user,
        );
    }
}