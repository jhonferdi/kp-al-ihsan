<?php
namespace App\Providers;

use Laravel\Socialite\Two\User;
use GuzzleHttp\Exception\GuzzleException;
use Laravel\Socialite\Two\AbstractProvider;
 
class SocialiteSSOProvider extends AbstractProvider
{
    /**
     * @var string[]
     */
    protected $scopes = [
        'openid',
    ];
 
    /**
     * @var string
     */
    protected $scopeSeparator = ' ';
 
    /**
     * @return string
     */
    public function getSSOUrl()
    {
        return config('services.sso.base_uri') . '/oauth';
    }
 
    /**
     * @param string $state
     *
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getSSOUrl() . '/authorize', $state);
    }
 
    /**
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->getSSOUrl() . '/token';
    }
 
    /**
     * @param string $token
     *
     * @throws GuzzleException
     *
     * @return array|mixed
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post($this->getSSOUrl() . '/userinfo', [
            'headers' => [
                'cache-control' => 'no-cache',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
 
        return json_decode($response->getBody()->getContents(), true);
    }
 
    /**
     * @return User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['sub'],
            'email' => $user['email'],
            'username' => $user['username'],
        ]);
    }
}
