<?php
namespace HtUserSocialAuthModule\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use HtUserSocialAuthModule\Manager\UserManager;

class UserManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \ZfcUser\Mapper\UserInterface $userMapper */
        $userMapper = $serviceLocator->get('zfcuser_user_mapper');

        return new UserManager($userMapper);
    }
}
