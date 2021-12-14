<?php

namespace App\Services\Mono;

use GuzzleHttp\Client;
use App\Responders\HttpResponseModel;
use GuzzleHttp\Exception\GuzzleException;

class MonoClient
{
    const METHODS = ['get', 'post', 'put', 'patch', 'delete', 'head'];
    const DEFAULT_METHOD = 'get';
    const SUCCESS_CODES = [200, 201, 202];

    private Client $client;
    private array $_body;
    private array $_headers;
    private string $_method;
    private string $_uri;
    private string $_baseUri;
    private string $_query;

    private string $apiSecret;

    public function __construct()
    {
        $this->apiSecret = config('services.mono.secret');
        $this->_baseUri = config('services.mono.api');
        $this->_method = 'GET';
        $this->_headers = [
            'Accept' => ' application/json',
            'mono-sec-key' => $this->apiSecret
        ];
        $this->client = new Client();
        $this->_body = [];
        $this->_query = "";
    }

    private function body(array $body)
    {
        $this->_body = array_merge($this->_body, $body);
        return $this;
    }

    private function query(array $query)
    {
        $this->_query = http_build_query($query);
        return $this;
    }

    private function headers(array $headers)
    {
        $this->_headers = array_merge($this->_headers, $headers);
        return $this;
    }

    private function uri($uri)
    {
        $this->_uri = $this->_baseUri . stripslashes(trim(rtrim($uri)));
        return $this;
    }

    private function method(string $method)
    {
        $this->_method = in_array(strtolower($method), self::METHODS)
            ? $method
            : self::DEFAULT_METHOD;

        return $this;
    }

    private function reportException(\Exception $e)
    {
        $this->reportError($e->getMessage(), $e->getLine());
    }

    private function reportError($message, $line)
    {
        // Handle Error
    }

    private function send(): HttpResponseModel
    {
        $_config = [];
        $_config['form_params'] = $this->_body;
        $_config['headers'] = $this->_headers;

        $status = false;
        $statusCode = 400;
        $data = [];
        $message = '';
        $error = '';
        $errors = [];
        if ($this->_query == "") {
            $this->query([]);
        }
        $this->_uri = "{$this->_uri}?{$this->_query}";

        try {
            $response = $this->client->request(
                $this->_method,
                $this->_uri,
                $_config
            );

            $statusCode = $response->getStatusCode();

            if (in_array($statusCode, self::SUCCESS_CODES)) {
                $status = true;
                $data = json_decode($response->getBody());
            }
        } catch (GuzzleException $e) {
            $status = false;
            $response = $e->getResponse()->getBody();
            $statusCode = $e->getCode();
            $body = json_decode($response, true);
            $message = $body['message'];
            $errors = $body;
        }

        return new HttpResponseModel(
            $status,
            $statusCode,
            $data,
            $message,
            $error,
            $errors,
        );
    }

    public function getExchangeToken(string $code): HttpResponseModel
    {
        $response = $this
            ->method('POST')
            ->uri('/account/auth')
            ->body([
                'code' => $code
            ])
            ->send();

        return $response;
    }

    public function getAccount(string $account): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/accounts/{$account}")
            ->send();

        return $response;
    }

    public function getTransactions(string $account, array $filter = []): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/accounts/{$account}/transactions")
            ->query($filter)
            ->send();

        return $response;
    }

    public function getStatements(string $account, string $period = "last6months", string $output = "json"): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/accounts/{$account}/statement")
            ->query([
                "period" => $period,
                "output" => $output
            ])
            ->send();

        return $response;
    }

    public function getCreditHistory(string $account): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/accounts/{$account}/credits")
            ->send();

        return $response;
    }

    public function getDebitHistory(string $account): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/accounts/{$account}/debits")
            ->send();

        return $response;
    }

    public function getAverageIncome(string $account): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/accounts/{$account}/income")
            ->send();

        return $response;
    }

    public function getIdentity(string $account): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/accounts/{$account}/identity")
            ->send();

        return $response;
    }

    public function syncData(string $account): HttpResponseModel
    {
        $response = $this
            ->method('POST')
            ->uri("/accounts/{$account}/sync")
            ->send();

        if ($response->data->status === "failed") {
            return new HttpResponseModel(
                false,
                400,
                json_decode(json_encode($response->data), true),
                $response->data->message,
                '',
            );
        }

        return $response;
    }

    public function getWalletBalance(): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/users/stats/wallet")
            ->send();

        return $response;
    }

    public function getInstitutions(): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/coverage")
            ->send();

        return $response;
    }

    public function lookupBusiness(string $businessId): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/v1/cac/lookup")
            ->query([
                "name" => $businessId
            ])
            ->send();

        return $response;
    }

    public function lookupShareholders(string $companyId): HttpResponseModel
    {
        $response = $this
            ->method('GET')
            ->uri("/v1/cac/company/{$companyId}")
            ->send();

        return $response;
    }
}
