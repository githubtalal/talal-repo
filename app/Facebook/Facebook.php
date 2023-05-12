<?php

namespace App\Facebook;

use GuzzleHttp\Client;

class Facebook
{
    const BASE_URL = 'https://graph.facebook.com/v14.0/';
    const USER_TOKEN_EXPIRATION_DAYS = 60;
    const LONG_LIVED_USER_TOKEN = 'long_lived_user_token';
    const PAGE_TOKEN = 'page_token';
    const SIGNATURE = 'facebook';

    /**
     * @var Client
     */
    private $httpClient;

    public static function create(): self
    {
        $instance = new self();
        $instance->httpClient = new Client([
            'base_uri' => self::BASE_URL,
            'verify' => false,
            'defaults' => [
                'query' => [
                    'grant_type' => 'fb_exchange_token',
                    'client_id' => config('hooks.facebook_app_id'),
                    'client_secret' => config('hooks.facebook_app_secret')
                ]
            ],
        ]);
        return $instance;
    }

    /**
     * @param string $userAccessToken Short lived user access token, valid for one hour.
     * @return FacebookAccessToken|null Long-lived User access tokens are valid for 60 days.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @link https://developers.facebook.com/docs/facebook-login/guides/access-tokens/get-long-lived
     */
    public function getLongLivedUserAccessToken($userAccessToken): ?FacebookAccessToken
    {
        try {
            $httpResponse = $this->httpClient->get('/oauth/access_token', [
                'query' => array_merge([
                    'fb_exchange_token' => $userAccessToken,
                ], $this->httpClient->getConfig('defaults')['query']),
            ]);

            $response = json_decode((string)$httpResponse->getBody());
            if ($httpResponse->getStatusCode() === 200)
                return FacebookAccessToken::parse($response);
            else {
                logger('[FACEBOOK CONNECT] Failed getting long lived access token:', [
                    'response' => $response,
                    'http_code' => $httpResponse->getStatusCode(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            logger('[FACEBOOK CONNECT] Error while getting long lived user token', [
                'user_access_token' => $userAccessToken,
                'exception' => $e->getMessage(),
            ]);
            report($e);
            return null;
        }
    }

    /**
     * @param string $userAccessToken Long-lived User access tokens
     * @param string $pageId Page id
     * @return FacebookAccessToken|null Long-lived Page access tokens are have no expiration date
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @link https://developers.facebook.com/docs/pages/access-tokens/
     */
    public function getLongLivedPageAccessToken($userAccessToken, $pageId): ?FacebookAccessToken
    {
        try {
            $httpResponse = $this->httpClient->get($pageId, [
                'query' => [
                    'fields' => 'access_token',
                    'access_token' => $userAccessToken
                ],
            ]);

            $response = json_decode((string)$httpResponse->getBody());
            if ($httpResponse->getStatusCode() === 200)
                return FacebookAccessToken::parse($response);
            else {
                logger('Facebook error while getting page access token:', [
                    'response' => $response,
                    'http_code' => $httpResponse->getStatusCode(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function getPagesFromUserAccessToken($userAccessToken)
    {
        try {
            $httpResponse = $this->httpClient->get('/me/accounts', [
                'query' => [
                    'type' => 'page',
                    'access_token' => $userAccessToken,
                ]
            ]);

            $response = json_decode((string)$httpResponse->getBody());
            if ($httpResponse->getStatusCode() === 200)
                return $response->data;
            return null;

        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function getPageProfilePicture($pageAccessToken, $size = 'large')
    {
        try {
            $httpResponse = $this->httpClient->get("/$pageAccessToken/picture", [
                'query' => [
                    'type' => $size,
                    'redirect' => 0,
                ]
            ]);

            $response = json_decode((string)$httpResponse->getBody());
            if ($httpResponse->getStatusCode() === 200)
                return $response->data;
            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public function subscribePageToWebHook($pageId, $pageAccessToken)
    {
        $client = new Client();
        $permissions = ['messages', 'messaging_postbacks'];
        $url = 'https://graph.facebook.com/' . $pageId . '/subscribed_apps?access_token=' . $pageAccessToken . '&subscribed_fields=' . implode(',', $permissions);
        try {
            $httpResponse = $client->post($url);

            $response = json_decode((string)$httpResponse->getBody());
            logger('[FACEBOOK CONNECT]  Result from facebook set-webhooks', [
                'response' => $response,
                'http_code' => $httpResponse->getStatusCode()
            ]);
            if ($httpResponse->getStatusCode() === 200)
                return $response->success === 'true' || $response->success === true;
            return false;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
