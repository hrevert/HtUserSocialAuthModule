<?php
namespace HtUserSocialAuthModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use HtLeagueOauthClientModule\Model\Oauth2User;

class SocialAuthenticationController extends AbstractActionController
{
    protected $failedLoginMessage = 'Social authentication failed';

    public function oauth2LoginAction()
    {
        $providerName = $this->params()->fromRoute('provider');

        $provider = $this->getProviderManager()->findByName($providerName);
        if (!$provider) {
            return $this->notFoundAction();
        }

        $providerClients = $this->getProviderClients();
        if (!$providerClients->has($providerName)) {
            return $this->notFoundAction();
        }
        /** @var \League\OAuth2\Client\Provider\ProviderInterface $providerClient */
        $providerClient = $providerClients->get($providerName);

        $authorizationCode = $this->params()->fromQuery('code', null);
        if (!$authorizationCode) {
            return $this->redirect()->toUrl($providerClient->getAuthorizationUrl());
        }

        $token = $providerClient->getAccessToken('authorization_code', [
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code'
        ]);

        /** @var \League\OAuth2\Client\Entity\User */
        $userDetails = $providerClient->getUserDetails($token);

        // access token is valid
        $userProviderLink = $this->getUserProviderManager()->findByProviderUid($userDetails->uid, $provider);

        if (!$userProviderLink) {
            // access token is valid but the user does not exists
            if (!$this->getZfcUserOptions()->getEnableRegistration()) {
                $this->flashMessenger()->setNamespace('zfcuser-index')->addMessage($this->failedLoginMessage);
            }

            $user = $this->getUserService()->create(new Oauth2User($userDetails));
        } else {
            $user = $userProviderLink->getUser();
        }

        $this->getAuthenticationStorage()->write($user);

        return $this->redirect()->toRoute('zfcuser');
    }

    /**
     * @return \Hrevert\OauthClient\Manager\ProviderManagerInterface
     */
    protected function getProviderManager()
    {
        return $this->getServiceLocator()->get('Hrevert\OauthClient\Manager\ProviderManager');
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected function getProviderClients()
    {
        return $this->getServiceLocator()->get('HtLeagueOauthClientModule\Oauth2ClientManager');
    }

    /**
     * @return \ZfcUser\Options\ModuleOptions
     */
    protected function getZfcUserOptions()
    {
        return $this->getServiceLocator()->get('zfcuser_user_options');
    }

    /**
     * @return \Hrevert\OauthClient\Manager\UserProviderManagerInterface
     */
    protected function getUserProviderManager()
    {
        return $this->getServiceLocator()->get('Hrevert\OauthClient\Manager\UserProviderManager');
    }

    /**
     * @return \Zend\Authentication\Storage\StorageInterface
     */
    protected function getAuthenticationStorage()
    {
        return $this->getServiceLocator()->get('ZfcUser\Authentication\Storage\Db');
    }

    /**
     * @return \HtUserSocialAuthModule\Service\UserService
     */
    protected function getUserService()
    {
        return $this->getServiceLocator()->get('HtUserSocialAuthModule\Service\UserService');
    }
}
