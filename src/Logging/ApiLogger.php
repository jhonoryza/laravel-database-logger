<?php

namespace Jhonoryza\DatabaseLogger\Logging;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jhonoryza\DatabaseLogger\Models\LogApi;

class ApiLogger
{
    /**
     * this should be register in boot ServiceProvider
     * usage Http::logRequest()->withHeaders([]) ..etc
     */
    public static function registerMacro(string $name = 'logRequest'): void
    {
        Http::macro($name, function () {
            return Http::withMiddleware(function (callable $handler) {
                return function ($request, array $options) use ($handler) {
                    $payload = $options['json'] ?? $options['form_params'] ?? null;

                    return $handler($request, $options)
                        ->then(function ($response) use ($request, $payload) {
                            try {
                                LogApi::create([
                                    'url' => (string) $request->getUri(),
                                    'method' => $request->getMethod(),
                                    'code' => $response->getStatusCode(),
                                    'header' => json_encode($request->getHeaders(), JSON_PRETTY_PRINT),
                                    'payload' => $payload ? json_encode($payload, JSON_PRETTY_PRINT) : null,
                                    'response' => (string) $response->getBody(),
                                ]);
                            } catch (\Throwable $e) {
                                Log::error('failed write to log api (exception): '.$e->getMessage());
                            }

                            return $response;
                        })
                        ->otherwise(function ($reason) use ($request, $payload) {
                            // failed (exception)
                            try {
                                LogApi::create([
                                    'url' => (string) $request->getUri(),
                                    'method' => $request->getMethod(),
                                    'status_code' => null,
                                    'header' => json_encode($request->getHeaders(), JSON_PRETTY_PRINT),
                                    'payload' => $payload ? json_encode($payload, JSON_PRETTY_PRINT) : null,
                                    'response' => $reason instanceof \Throwable
                                        ? $reason->getMessage()
                                        : json_encode($reason),
                                ]);
                            } catch (\Throwable $e) {
                                Log::error('failed write to log api (exception): '.$e->getMessage());
                            }

                            throw $reason; // rethrow it so it can still be caught by the caller
                        });
                };
            });
        });
    }
}
