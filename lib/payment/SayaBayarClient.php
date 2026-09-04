<?php
declare(strict_types=1);

/**
 * SayaBayar payment adapter.
 *
 * SayaBayar publicly confirms API-key integration, automatic invoice matching,
 * QRIS/bank channels and webhook support. The exact API endpoint URLs are kept
 * configurable because their public site does not expose the developer endpoint
 * paths in its indexed documentation.
 */
final class SayaBayarClient
{
    public function __construct(
        private string $createUrl,
        private string $checkUrl,
        private string $apiKey,
        private int $timeout = 30
    ) {
        $this->createUrl = trim($this->createUrl);
        $this->checkUrl = trim($this->checkUrl);
        $this->apiKey = trim($this->apiKey);
        if ($this->createUrl === '' || $this->apiKey === '') {
            throw new InvalidArgumentException('Konfigurasi SayaBayar belum lengkap.');
        }
    }

    public function createInvoice(int $amount, string $reference, string $callbackUrl, array $extra = []): array
    {
        $payload = array_merge([
            'amount' => $amount,
            'reference_id' => $reference,
            'callback_url' => $callbackUrl,
        ], $extra);
        return $this->request($this->createUrl, 'POST', $payload);
    }

    public function checkInvoice(string $invoiceId): array
    {
        if ($this->checkUrl === '') throw new RuntimeException('URL check invoice SayaBayar belum dikonfigurasi.');
        $separator = str_contains($this->checkUrl, '?') ? '&' : '?';
        return $this->request($this->checkUrl . $separator . 'invoice=' . rawurlencode($invoiceId), 'GET');
    }

    private function request(string $url, string $method, ?array $payload = null): array
    {
        $ch = curl_init($url);
        $headers = ['Accept: application/json', 'Authorization: Bearer ' . $this->apiKey];
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => max(5, $this->timeout),
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
        ];
        if ($payload !== null) {
            $headers[] = 'Content-Type: application/json';
            $options[CURLOPT_HTTPHEADER] = $headers;
            $options[CURLOPT_POSTFIELDS] = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        }
        curl_setopt_array($ch, $options);
        $raw = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($errno) throw new RuntimeException('SayaBayar connection error: ' . $error);
        $json = json_decode((string)$raw, true);
        if (!is_array($json)) throw new RuntimeException('Respons SayaBayar bukan JSON. HTTP ' . $status);
        if ($status < 200 || $status >= 300) throw new RuntimeException('SayaBayar HTTP ' . $status);
        return $json;
    }
}
