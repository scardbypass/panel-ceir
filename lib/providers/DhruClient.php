<?php
declare(strict_types=1);

final class DhruClient
{
    private string $url;
    private string $username;
    private string $apiAccessKey;
    private int $timeout;

    public function __construct(string $url, string $username, string $apiAccessKey, int $timeout = 30)
    {
        $this->url = rtrim(trim($url), '/');
        $this->username = trim($username);
        $this->apiAccessKey = trim($apiAccessKey);
        $this->timeout = max(5, $timeout);
        if ($this->url === '' || $this->username === '' || $this->apiAccessKey === '') {
            throw new InvalidArgumentException('Konfigurasi DHRU belum lengkap.');
        }
    }

    public function accountInfo(): array { return $this->call('accountinfo'); }
    public function getProducts(): array { return $this->call('imeiservicelist'); }
    public function placeOrder(array $parameters): array
    {
        return $this->call('placeimeiorder', [
            'requestformat' => 'JSON',
            'parameters' => json_encode($parameters, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        ]);
    }
    public function getOrder(string|int $id): array
    {
        return $this->call('getimeiorder', [
            'requestformat' => 'JSON',
            'parameters' => json_encode(['ID' => (string)$id], JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
        ]);
    }

    private function call(string $action, array $extra = []): array
    {
        $fields = array_merge([
            'username' => $this->username,
            'apiaccesskey' => $this->apiAccessKey,
            'action' => $action,
            'requestformat' => 'JSON',
        ], $extra);
        $ch = curl_init($this->url . '/api/index.php');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($fields, '', '&', PHP_QUERY_RFC3986),
            CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $raw = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($errno !== 0) throw new RuntimeException('DHRU connection error: ' . $error);
        if ($status < 200 || $status >= 300) throw new RuntimeException('DHRU HTTP ' . $status);
        $json = json_decode((string)$raw, true);
        if (!is_array($json)) throw new RuntimeException('Respons DHRU bukan JSON yang valid.');
        if (isset($json['ERROR'])) throw new RuntimeException((string)($json['ERROR'][0]['MESSAGE'] ?? 'DHRU menolak request.'));
        return $json;
    }
}
