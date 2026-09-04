<?php
/**
 * Secure CeirGO API client.
 * Docs: https://ceirgo.id/docs
 */
class CeirGoClient
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout;

    public function __construct(string $apiKey, string $baseUrl = 'https://ceirgo.id', int $timeout = 30)
    {
        $this->apiKey = trim($apiKey);
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = max(5, $timeout);
        if ($this->apiKey === '') {
            throw new InvalidArgumentException('CeirGO API key belum dikonfigurasi.');
        }
    }

    public function me(): array
    {
        return $this->request('GET', '/api/me');
    }

    public function wallet(): array
    {
        return $this->request('GET', '/api/wallet/snap');
    }

    public function services(int $limit = 50, ?int $cursor = null): array
    {
        $query = ['limit' => max(1, min(50, $limit))];
        if ($cursor !== null) $query['cursor'] = $cursor;
        return $this->request('GET', '/api/services?' . http_build_query($query));
    }

    public function service(string $idOrCode): array
    {
        return $this->request('GET', '/api/services/' . rawurlencode($idOrCode));
    }

    public function createOrder(string $code, array $data): array
    {
        return $this->request('POST', '/api/order', ['code' => $code, 'data' => $data]);
    }

    public function order(int $id): array
    {
        return $this->request('GET', '/api/order/' . $id);
    }

    public function orders(array $filters = []): array
    {
        $allowed = ['q','status','processing_type','service_code','created_from','created_to','limit','cursor'];
        $query = [];
        foreach ($allowed as $key) {
            if (array_key_exists($key, $filters) && $filters[$key] !== null && $filters[$key] !== '') {
                $query[$key] = $filters[$key];
            }
        }
        return $this->request('GET', '/api/order' . ($query ? '?' . http_build_query($query) : ''));
    }

    private function request(string $method, string $path, ?array $body = null): array
    {
        $ch = curl_init($this->baseUrl . $path);
        $headers = [
            'Authorization: Bearer ' . $this->apiKey,
            'Accept: application/json',
        ];
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ];
        if ($body !== null) {
            $headers[] = 'Content-Type: application/json';
            $options[CURLOPT_HTTPHEADER] = $headers;
            $options[CURLOPT_POSTFIELDS] = json_encode($body, JSON_UNESCAPED_SLASHES);
        }
        curl_setopt_array($ch, $options);
        $raw = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno) throw new RuntimeException('CeirGO connection error: ' . $error);
        $json = json_decode((string)$raw, true);
        if (!is_array($json)) throw new RuntimeException('CeirGO mengembalikan response bukan JSON. HTTP ' . $status);
        if ($status < 200 || $status >= 300) {
            $message = $json['message'] ?? $json['error']['message'] ?? ('HTTP ' . $status);
            throw new RuntimeException('CeirGO API: ' . $message, $status);
        }
        return $json;
    }

    public static function verifyWebhook(string $rawBody, string $signature, string $apiKey, array $payload): bool
    {
        $secret = $apiKey;
        $dot = strpos($apiKey, '.');
        if ($dot !== false) $secret = substr($apiKey, $dot + 1);

        $status = $payload['status'] ?? '';
        if ($status === 'pending') {
            $amount = (string)($payload['total_price'] ?? 0);
        } elseif (in_array($status, ['failed', 'cancelled'], true)) {
            $amount = '0';
        } else {
            $amount = (string)($payload['charged_amount'] ?? 0);
        }
        $signed = json_encode(['orderId' => (int)($payload['order_id'] ?? 0), 'amount' => $amount], JSON_UNESCAPED_SLASHES);
        $expected = hash_hmac('sha256', $signed, $secret);
        return is_string($signature) && hash_equals($expected, $signature);
    }
}
