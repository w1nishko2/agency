<?php

namespace App\Services;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class VkIdProvider extends AbstractProvider
{
    protected $scopes = ['email'];
    
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://id.vk.com/authorize', $state);
    }
    
    protected function getTokenUrl()
    {
        return 'https://id.vk.com/oauth2/auth';
    }
    
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post('https://id.vk.com/oauth2/user_info', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'access_token' => $token,
                'client_id' => $this->clientId,
            ],
        ]);
        
        return json_decode($response->getBody(), true);
    }
    
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['user']['user_id'] ?? null,
            'name' => trim(($user['user']['first_name'] ?? '') . ' ' . ($user['user']['last_name'] ?? '')),
            'email' => $user['user']['email'] ?? null,
            'avatar' => $user['user']['avatar'] ?? null,
        ]);
    }
    
    protected function getTokenFields($code)
    {
        return [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'code_verifier' => 'w0HHflUXqpJwP0Lw4eZxELzwIXKHwdwnOMER',
            'client_id' => $this->clientId,
            'device_id' => request('device_id', 'web_' . time()),
            'redirect_uri' => $this->redirectUrl,
        ];
    }
    
    public function getAccessTokenResponse($code)
    {
        \Log::info('VK ID: Getting access token', [
            'code' => $code,
            'device_id' => request('device_id'),
            'redirect_uri' => $this->redirectUrl
        ]);
        
        try {
            $response = $this->getHttpClient()->post($this->getTokenUrl(), [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                ],
                'form_params' => $this->getTokenFields($code),
            ]);

            $result = json_decode($response->getBody(), true);
            
            \Log::info('VK ID: Access token response', [
                'has_access_token' => isset($result['access_token']),
                'response' => $result
            ]);
            
            return $result;
        } catch (\Exception $e) {
            \Log::error('VK ID: Token exchange failed', [
                'error' => $e->getMessage(),
                'code' => $code
            ]);
            throw $e;
        }
    }
}
