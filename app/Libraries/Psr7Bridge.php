<?php

namespace App\Libraries;

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\IncomingRequest;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use CodeIgniter\HTTP\Response as CI4Response;

class Psr7Bridge {
    /**
     * Creates a PSR-7 Server Request instance from a CodeIgniter4 IncomingRequest object.
     */
    public static function createServerRequest(IncomingRequest $request) {
        $factory    = new Psr17Factory();
        $psrRequest = $factory->createServerRequest(
            $request->getMethod(true),
            $request->getUri()->__toString(),
            $request->getServer()
        );

        // Add headers
        foreach ($request->headers() as $header) {
            try {
                $psrRequest = $psrRequest->withHeader($header->getName(), $header->getValue());
            } catch (\Throwable $th) {
                // ignore invalid, for convenience
            }
        }

        // Fetch query params from URI
        parse_str($request->getUri()->getQuery(), $queryArr);

        return $psrRequest
            ->withBody($factory->createStream($request->getBody() ?? ''))
            ->withCookieParams($request->getCookie())
            ->withUploadedFiles($request->getFiles())
            ->withQueryParams($queryArr)
            ->withParsedBody($request->getVar());
    }

    /**
     * Creates a PSR-7 Response instance from a CodeIgniter4 Response object.
     */
    public static function createResponse(CI4Response $response) {
        $factory     = new Psr17Factory();
        $psrResponse = $factory->createResponse($response->getStatusCode(), $response->getReasonPhrase());

        // Add headers and cookies
        foreach ($response->headers() as $header) {
            try {
                $psrResponse = $psrResponse->withHeader($header->getName(), $header->getValue());
            } catch (\Throwable $th) {
                // ignore invalid, for convenience
            }
        }
        if (!empty($response->getCookies())) {
            $cookies = [];
            foreach ($response->getCookies() as $cookie) {
                $cookies[] = $cookie->__toString();
            }
            $psrResponse->withHeader('Set-Cookie', $cookies);
        }

        return $psrResponse
            ->withProtocolVersion($response->getProtocolVersion())
            ->withBody($factory->createStream($response->getBody() ?? ''));
    }

    /**
     * Creates a CI4 Response instance from a PSR-7 Response instance.
     */
    public static function createCI4Response(ResponseInterface $response) {
        $ci4Response = Services::response();

        // Add header
        foreach ($response->getHeaders() as $name => $value) {
            try {
                $ci4Response->setHeader($name, $value);
            } catch (\Throwable $th) {
                // ignore invalid, for convenience
            }
        }

        // rewind response body stream so we could retrive its content from the begining
        $response->getBody()->rewind();

        return $ci4Response
            ->setProtocolVersion($response->getProtocolVersion())
            ->setStatusCode($response->getStatusCode(), $response->getReasonPhrase())
            ->setBody($response->getBody()->getContents());
    }
}