<?php


namespace App\Tests\Framework\Controller\API;


trait TokenHelperTrait
{
    private function getToken(): string
    {
        $postData = [
            'email' => 'test@domain.com',
            'password' => '12345678',
        ];
        $this->client->request('POST', '/api/rental/users/tokens', [], [], [], json_encode($postData));
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        return $responseContent['token'];
    }

    private function getAdminToken(): string
    {
        $postData = [
            'email' => 'testadmin@domain.com',
            'password' => '12345678',
        ];
        $this->client->request('POST', '/api/administration/users/tokens', [], [], [], json_encode($postData));
        $response = $this->client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        return $responseContent['token'];
    }
}
