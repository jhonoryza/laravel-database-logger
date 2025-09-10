<?php

namespace Jhonoryza\DatabaseLogger\Logging;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jhonoryza\DatabaseLogger\Models\LogApi;

class ApiLogger
{
    /**
     * Register Http macro for logging API request/response.
     * This should be register in boot ServiceProvider
     * Usage: Http::logRequest()->withHeaders([...])->post(...);
     */
    public static function registerMacro(string $name = 'logRequest'): void
    {
        Http::macro($name, function () {
            return Http::withMiddleware(function (callable $handler) {
                return function ($request, array $options) use ($handler) {

                    // Normalize payload (json, form, multipart, raw body)
                    $payload = $options['json']
                        ?? $options['form_params']
                        ?? $options['multipart']
                        ?? $options['body']
                        ?? null;

                    $normalizePayload = function ($payload) {
                        if (is_array($payload)) {
                            // Handle multipart (file uploads)
                            if (isset($payload[0]['contents'])) {
                                foreach ($payload as &$part) {
                                    if (isset($part['contents']) && is_resource($part['contents'])) {
                                        $part['contents'] = '[binary stream]';
                                    }
                                }
                            }

                            return json_encode($payload, JSON_PRETTY_PRINT);
                        }

                        if (is_object($payload)) {
                            return json_encode($payload, JSON_PRETTY_PRINT);
                        }

                        if (is_string($payload)) {
                            return mb_strlen($payload) > 5000
                                ? mb_substr($payload, 0, 5000).'... [truncated]'
                                : $payload;
                        }

                        return $payload;
                    };

                    $payload = $normalizePayload($payload);

                    // Fallback ambil dari request body
                    if ($payload === null) {
                        $payload = (string) $request->getBody();
                        $contentType = $request->getHeaderLine('Content-Type');

                        if (str_contains($contentType, 'application/json')) {
                            $decoded = json_decode($payload, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $payload = json_encode($decoded, JSON_PRETTY_PRINT);
                            }
                        }
                    }

                    // Execute request
                    return $handler($request, $options)
                        ->then(function ($response) use ($request, $payload) {
                            try {
                                LogApi::create([
                                    'url' => (string) $request->getUri(),
                                    'method' => $request->getMethod(),
                                    'code' => $response->getStatusCode(),
                                    'header' => json_encode($request->getHeaders(), JSON_PRETTY_PRINT),
                                    'payload' => $payload,
                                    'response' => mb_strlen((string) $response->getBody()) > 5000
                                                            ? mb_substr((string) $response->getBody(), 0, 5000).'... [truncated]'
                                                            : (string) $response->getBody(),
                                    'response_header' => json_encode($response->getHeaders(), JSON_PRETTY_PRINT),
                                ]);
                            } catch (\Throwable $e) {
                                Log::error('Failed to write API log (success): '.$e->getMessage());
                            }

                            return $response;
                        })
                        ->otherwise(function ($reason) use ($request, $payload) {
                            // Catch exceptions (timeout, DNS error, etc.)
                            try {
                                LogApi::create([
                                    'url' => (string) $request->getUri(),
                                    'method' => $request->getMethod(),
                                    'code' => null,
                                    'header' => json_encode($request->getHeaders(), JSON_PRETTY_PRINT),
                                    'payload' => $payload,
                                    'response' => $reason instanceof \Throwable
                                                            ? $reason->getMessage()
                                                            : json_encode($reason),
                                    'response_header' => null,
                                ]);
                            } catch (\Throwable $e) {
                                Log::error('Failed to write API log (exception): '.$e->getMessage());
                            }

                            throw $reason; // rethrow so caller can still handle
                        });
                };
            });
        });
    }
}
