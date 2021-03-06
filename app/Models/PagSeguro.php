<?php

namespace App\Models;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagSeguro extends Model
{
    use HasFactory;

    /**
     * @return \SimpleXMLElement
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function generate()
    {
        $guzzle = new Guzzle;
        $params = [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
            'currency' => 'BRL',
            'itemId1' => '0001',
            'itemDescription1' => 'Produto PagSeguroI',
            'itemAmount1' => '99999.99',
            'itemQuantity1' => '1',
            'itemWeight1' => '1000',
            'itemId2' => '0002',
            'itemDescription2' => 'Produto PagSeguroII',
            'itemAmount2' => '99999.98',
            'itemQuantity2' => '2',
            'itemWeight2' => '750',
            'reference' => 'REF1234',
            'senderName' => 'Jose Comprador',
            'senderAreaCode' => '99',
            'senderPhone' => '999999999',
            'senderEmail' => 'comprador@uol.com.br',
            'shippingType' => '1',
            'shippingAddressStreet' => 'Av. PagSeguro',
            'shippingAddressNumber' => '9999',
            'shippingAddressComplement' => '99º andar',
            'shippingAddressDistrict' => 'Jardim Internet',
            'shippingAddressPostalCode' => '99999999',
            'shippingAddressCity' => 'Cidade Exemplo',
            'shippingAddressState' => 'SP',
            'shippingAddressCountry' => 'ATA',
        ];
        $params = http_build_query($params);
        $response = $guzzle->request('POST', config('pagseguro.url_checkout_sandbox'), [
            'query' => $params,
        ]);
        $body = $response->getBody();
        $contents = $body->getContents();
        $xml = simplexml_load_string($contents);
        $code = $xml->code;
        return $code;
    }

    /**
     * @return \SimpleXMLElement
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSessionId()
    {
        $params = [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
        ];
        $params = http_build_query($params);
        $guzzle = new Guzzle;
        $response = $guzzle->request('POST', config('pagseguro.url_transparente_session_sandbox'), [
            'query' => $params,
        ]);
        $body = $response->getBody();
        $contents = $body->getContents();
        $xml = simplexml_load_string($contents);
        return $xml->id;
    }

    /**
     * @param $sendHash
     * @return \SimpleXMLElement
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function paymentBillet($sendHash)
    {
        $guzzle = new Guzzle;
        $params = [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
            'senderHash' => $sendHash,
            'paymentMode' => 'default',
            'paymentMethod' => 'boleto',
            'currency' => 'BRL',
            'itemId1' => '0001',
            'itemDescription1' => 'Produto PagSeguroI',
            'itemAmount1' => '99999.99',
            'itemQuantity1' => '1',
            'itemWeight1' => '1000',
            'itemId2' => '0002',
            'itemDescription2' => 'Produto PagSeguroII',
            'itemAmount2' => '99999.98',
            'itemQuantity2' => '2',
            'itemWeight2' => '750',
            'reference' => 'REF1234',
            'senderName' => 'Jose Comprador',
            'senderAreaCode' => '99',
            'senderPhone' => '999999999',
            'senderEmail' => 'c20689406015405234397@sandbox.pagseguro.com.br',
            'senderCPF' => '54793120652',
            'shippingType' => '1',
            'shippingAddressStreet' => 'Av. PagSeguro',
            'shippingAddressNumber' => '9999',
            'shippingAddressComplement' => '99º andar',
            'shippingAddressDistrict' => 'Jardim Internet',
            'shippingAddressPostalCode' => '99999999',
            'shippingAddressCity' => 'Cidade Exemplo',
            'shippingAddressState' => 'SP',
            'shippingAddressCountry' => 'ATA',
        ];
        $response = $guzzle->request('POST', config('pagseguro.url_transparente_sandbox'), [
            'form_params' => $params,
        ]);
        $body = $response->getBody();
        $contents = $body->getContents();
        $xml = simplexml_load_string($contents);
        return $xml->paymentLink;
    }

    public function paymentCredCard($request)
    {
        $guzzle = new Guzzle;
        $params = [
            'email' => config('pagseguro.email'),
            'token' => config('pagseguro.token'),
            'senderHash' => $request->senderHash,
            'paymentMode' => 'default',
            'paymentMethod' => 'boleto',
            'currency' => 'BRL',
            'itemId1' => '0001',
            'itemDescription1' => 'Produto PagSeguroI',
            'itemAmount1' => '99999.99',
            'itemQuantity1' => '1',
            'itemWeight1' => '1000',
            'itemId2' => '0002',
            'itemDescription2' => 'Produto PagSeguroII',
            'itemAmount2' => '99999.98',
            'itemQuantity2' => '2',
            'itemWeight2' => '750',
            'reference' => 'REF1234',
            'senderName' => 'Jose Comprador',
            'senderAreaCode' => '99',
            'senderPhone' => '999999999',
            'senderEmail' => 'c20689406015405234397@sandbox.pagseguro.com.br',
            'senderCPF' => '54793120652',
            'shippingType' => '1',
            'shippingAddressStreet' => 'Av. PagSeguro',
            'shippingAddressNumber' => '9999',
            'shippingAddressComplement' => '99º andar',
            'shippingAddressDistrict' => 'Jardim Internet',
            'shippingAddressPostalCode' => '99999999',
            'shippingAddressCity' => 'Cidade Exemplo',
            'shippingAddressState' => 'SP',
            'shippingAddressCountry' => 'ATA',
            'creditCardToken' => $request->cardToken,
            'installmentQuantity' => 1,
            'installmentValue' => 300021.65,
            'noInterestInstallmentQuantity' => 2,
            'creditCardHolderName' => 'Jose Comprador',
            'creditCardHolderCPF' => '11475714734',
            'creditCardHolderBirthDate' => '01/01/1900',
            'creditCardHolderAreaCode' => '99',
            'creditCardHolderPhone' => '999999999',
            'billingAddressStreet' => 'Av. PagSeguro',
            'billingAddressNumber' => '9999',
            'billingAddressComplement' => '99º andar',
            'billingAddressDistrict' => 'Jardim Internet',
            'billingAddressPostalCode' => '99999999',
            'billingAddressCity' => 'Cidade Exemplo',
            'billingAddressState' => 'SP',
            'billingAddressCountry' => 'ATA',
        ];
        $response = $guzzle->request('POST', config('pagseguro.url_transparente_sandbox'), [
            'form_params' => $params,
        ]);
        $body = $response->getBody();
        $contents = $body->getContents();
        $xml = simplexml_load_string($contents);
        return $xml->code;
    }
}
