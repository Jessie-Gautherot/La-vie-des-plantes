<?php

namespace WPForms\Vendor\Core\Tests;

use WPForms\Vendor\apimatic\jsonmapper\JsonMapperException;
use WPForms\Vendor\Core\Client;
use WPForms\Vendor\Core\ClientBuilder;
use WPForms\Vendor\Core\Request\Parameters\BodyParam;
use WPForms\Vendor\Core\Request\Parameters\FormParam;
use WPForms\Vendor\Core\Request\Parameters\HeaderParam;
use WPForms\Vendor\Core\Request\Parameters\QueryParam;
use WPForms\Vendor\Core\Request\Parameters\TemplateParam;
use WPForms\Vendor\Core\Request\Request;
use WPForms\Vendor\Core\Response\Context;
use WPForms\Vendor\Core\Response\ResponseHandler;
use WPForms\Vendor\Core\Tests\Mocking\MockConverter;
use WPForms\Vendor\Core\Tests\Mocking\MockHelper;
use WPForms\Vendor\Core\Tests\Mocking\MockHttpClient;
use WPForms\Vendor\CoreInterfaces\Core\Response\ResponseInterface;
use WPForms\Vendor\CoreInterfaces\Http\HttpClientInterface;
use Exception;
use InvalidArgumentException;
use WPForms\Vendor\PHPUnit\Framework\TestCase;
class ClientTest extends TestCase
{
    public function testHttpClient()
    {
        $httpClient = MockHelper::getClient()->getHttpClient();
        $this->assertInstanceOf(HttpClientInterface::class, $httpClient);
        $request = new Request('https://some/path');
        $response = $httpClient->execute($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['content-type' => 'application/json'], $response->getHeaders());
        $this->assertIsObject($response->getBody());
        $this->assertEquals('{"body":{"httpMethod":"Get","queryUrl":"https:\\/\\/some\\/path","headers":[],' . '"parameters":[],"parametersEncoded":[],"parametersMultipart":[],"body":null,' . '"retryOption":"useGlobalSettings"},"additionalProperties":[]}', $response->getRawBody());
    }
    public function testClientInstanceWithDifferentConfig()
    {
        $client = ClientBuilder::init(new MockHttpClient())->converter(new MockConverter())->serverUrls(['Server1' => 'http://my/path:3000/{one}', 'Server2' => 'https://my/path/{two}'], 'Server1')->jsonHelper(MockHelper::getJsonHelper())->apiCallback("my call back")->build();
        $this->assertInstanceOf(Client::class, $client);
        $request = $client->getGlobalRequest();
        $this->assertInstanceOf(Request::class, $request);
        $client->beforeRequest($request);
        $client->afterResponse(new Context($request, MockHelper::getResponse(), $client));
        $responseHandler = $client->getGlobalResponseHandler();
        $this->assertInstanceOf(ResponseHandler::class, $responseHandler);
    }
    public function testApplyingParamsWithoutValidation()
    {
        $request = MockHelper::getClient()->getGlobalRequest();
        $request->appendPath('/{newKey}');
        $queryUrl = $request->getQueryUrl();
        $headers = $request->getHeaders();
        $parameters = $request->getParameters();
        $body = $request->getBody();
        QueryParam::init('newKey', 'newVal')->apply($request);
        $this->assertEquals($request->getQueryUrl(), $queryUrl);
        TemplateParam::init('newKey', 'newVal')->apply($request);
        $this->assertEquals($request->getQueryUrl(), $queryUrl);
        HeaderParam::init('newKey', 'newVal')->apply($request);
        $this->assertEquals($request->getHeaders(), $headers);
        FormParam::init('newKey', 'newVal')->apply($request);
        $this->assertEquals($request->getParameters(), $parameters);
        BodyParam::init('newVal')->apply($request);
        $this->assertEquals($request->getBody(), $body);
    }
    /**
     * @throws Exception
     */
    public function fakeSerializeBy($argument)
    {
        throw new Exception('Invalid argument found');
    }
    public function testRequiredQueryParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required query field: newKey");
        QueryParam::init('newKey', null)->required()->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testSerializeByQueryParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to serialize field: newKey, Due to:\nInvalid argument found");
        QueryParam::init('newKey', 'someVal')->serializeBy([$this, 'fakeSerializeBy'])->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testStrictTypeQueryParamValidation()
    {
        $this->expectException(JsonMapperException::class);
        $this->expectExceptionMessage("Unable to map Type: string on: oneof(int,bool)");
        QueryParam::init('newKey', 'someVal')->strictType('oneof(int,bool)')->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testRequiredTemplateParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required template field: newKey");
        TemplateParam::init('newKey', null)->required()->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testSerializeByTemplateParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to serialize field: newKey, Due to:\nInvalid argument found");
        TemplateParam::init('newKey', 'someVal')->serializeBy([$this, 'fakeSerializeBy'])->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testStrictTypeTemplateParamValidation()
    {
        $this->expectException(JsonMapperException::class);
        $this->expectExceptionMessage("Unable to map Type: string on: oneof(int,bool)");
        TemplateParam::init('newKey', 'someVal')->strictType('oneof(int,bool)')->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testRequiredFormParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required form field: newKey");
        FormParam::init('newKey', null)->required()->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testSerializeByFormParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to serialize field: newKey, Due to:\nInvalid argument found");
        FormParam::init('newKey', 'someVal')->serializeBy([$this, 'fakeSerializeBy'])->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testStrictTypeFormParamValidation()
    {
        $this->expectException(JsonMapperException::class);
        $this->expectExceptionMessage("Unable to map Type: string on: oneof(int,bool)");
        FormParam::init('newKey', 'someVal')->strictType('oneof(int,bool)')->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testRequiredHeaderParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required header field: newKey");
        HeaderParam::init('newKey', null)->required()->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testSerializeByHeaderParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to serialize field: newKey, Due to:\nInvalid argument found");
        HeaderParam::init('newKey', 'someVal')->serializeBy([$this, 'fakeSerializeBy'])->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testStrictTypeHeaderParamValidation()
    {
        $this->expectException(JsonMapperException::class);
        $this->expectExceptionMessage("Unable to map Type: string on: oneof(int,bool)");
        HeaderParam::init('newKey', 'someVal')->strictType('oneof(int,bool)')->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testRequiredBodyParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required body field: body");
        BodyParam::init(null)->required()->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testSerializeByBodyParamValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unable to serialize field: body, Due to:\nInvalid argument found");
        BodyParam::init('someVal')->serializeBy([$this, 'fakeSerializeBy'])->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testStrictTypeBodyParamValidation()
    {
        $this->expectException(JsonMapperException::class);
        $this->expectExceptionMessage("Unable to map Type: string on: oneof(int,bool)");
        BodyParam::init('someVal')->strictType('oneof(int,bool)')->validate(Client::getJsonHelper(MockHelper::getClient()));
    }
    public function testRequestInitializationWithCustomBaseUrl()
    {
        $customUrl = 'https://my/path/';
        $customUrlWithoutSlash = 'https://my/path';
        $client = ClientBuilder::init(new MockHttpClient())->converter(new MockConverter())->apiCallback(MockHelper::getCallbackCatcher())->jsonHelper(MockHelper::getJsonHelper())->serverUrls(['ServerA' => '{custom-url-a}', 'ServerB' => '{custom-url-b}'], 'ServerA')->globalConfig([TemplateParam::init('custom-url-a', $customUrl)->dontEncode(), TemplateParam::init('custom-url-b', $customUrlWithoutSlash)->dontEncode()])->build();
        $requestA = $client->getGlobalRequest('ServerA');
        $this->assertEquals($customUrlWithoutSlash, $requestA->getQueryUrl());
        $requestB = $client->getGlobalRequest('ServerB');
        $this->assertEquals($customUrlWithoutSlash, $requestB->getQueryUrl());
    }
}
