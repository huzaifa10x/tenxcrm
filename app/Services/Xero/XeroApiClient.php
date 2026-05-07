<?php

namespace App\Services\Xero;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class XeroApiClient
{
    private const BASE_URL      = 'https://api.xero.com/api.xro/2.0/';
    private const MAX_PAGE_SIZE = 100;
    private const RATE_KEY      = 'xero_api_calls';
    private const RATE_LIMIT    = 55;

    public function __construct(
        private readonly XeroAuthService $auth
    ) {}

    // ── Public pagination helpers ──────────────────────────────────

    public function fetchAll(
        string  $endpoint,
        array   $params = [],
        string  $dataKey = null,
        ?string $modifiedSince = null
    ): array {
        $dataKey = $dataKey ?? $endpoint;
        $all     = [];
        $page    = 1;

        do {
            $response = $this->get($endpoint, array_merge($params, [
                'page'     => $page,
                'pageSize' => self::MAX_PAGE_SIZE,
            ]), $modifiedSince);

            $records = $response[$dataKey] ?? [];
            $all     = array_merge($all, $records);

            Log::info("Xero: Fetched page {$page} of {$endpoint} — " . count($records) . ' records.');

            $page++;
            
            // FIX: Xero will often return fewer than MAX_PAGE_SIZE records on a page
            // if server-side filtering (like 'where' clauses) is applied, or if you simply 
            // hit the last page. We only stop when 0 records are returned.
        } while (count($records) > 0);

        return $all;
    }

    public function get(string $endpoint, array $params = [], ?string $modifiedSince = null): array
    {
        $this->throttle();

        $headers = [
            'Authorization'  => 'Bearer ' . $this->auth->getAccessToken(),
            'Xero-Tenant-Id' => $this->auth->getTenantId(),
            'Accept'         => 'application/json',
        ];

        if ($modifiedSince) {
            $headers['If-Modified-Since'] = $modifiedSince;
        }

        $attempt = 0;

        retry:
        $attempt++;

        $response = Http::withHeaders($headers)
            ->timeout(30)
            ->get(self::BASE_URL . $endpoint, $params);

        if ($response->status() === 429) {
            if ($attempt <= 3) {
                $waitSeconds = (int) ($response->header('Retry-After') ?? (60 * $attempt));
                Log::warning("Xero: Rate limited. Waiting {$waitSeconds}s before retry.");
                sleep($waitSeconds);
                goto retry;
            }
            throw new \RuntimeException("Xero API rate limit exceeded after {$attempt} retries.");
        }

        if ($response->status() === 503) {
            if ($attempt <= 2) {
                sleep(5 * $attempt);
                goto retry;
            }
        }

        if ($response->failed()) {
            throw new \RuntimeException(
                "Xero API error [{$response->status()}] on {$endpoint}: " . $response->body()
            );
        }

        return $response->json() ?? [];
    }

    // ── Private helpers ────────────────────────────────────────────

    private function throttle(): void
    {
        if (RateLimiter::tooManyAttempts(self::RATE_KEY, self::RATE_LIMIT)) {
            $seconds = RateLimiter::availableIn(self::RATE_KEY);
            Log::info("Xero: Self-throttling for {$seconds}s.");
            sleep($seconds + 1);
        }

        RateLimiter::hit(self::RATE_KEY, 60);
    }
}