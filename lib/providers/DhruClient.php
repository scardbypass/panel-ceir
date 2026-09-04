<?php
/** Generic Dhru Fusion API client. */
class DhruClient
{
    private string $url;
    private string $username;
    private string $apiAccessKey;
    private int $timeout;

    public function __construct(string $url, string $username, string $apiAccessKey, int $timeout = 30)
    {
        $this->url = rtrim($url, '/');
        $this->username = trim($username);
        $this->apiAccessKey = trim($apiAccessKey);
        $this->timeout = max(5, $timeout);
        if ($this->url === '' || $this->username === '' || $this->apiAccessKey === '') {
            throw new InvalidArgumentException('Konfigurasi Dhru belum lengkap.');
        }
    }

    public function getProducts(): array
    {
        return $this->call('imeiservicelist', ['mode' => '']);
    }

    public function placeOrder(array $request): array
    {
        return $this->call('placeimeiorder', [
            'requestformat' => 'JSON',
            'parameters' => json_encode($request, JSON_UNESCAPED_SLASHES),
        ]);
    }

    public function getOrder(string $id): array
    {
        return $this->call('getimeiorder', [
            'requestformat' => 'JSON',
            'parameters' => json_encode(['ID' => $id]),
        ]);
    }

    private function call(string $action, array $extra): array
    {
        $fields = array_merge([
            'username' => $this->username,
            'apiaccesskey' => $this->apiAccessKey,
            'action' => $action,
        ], $extra);

        $ch = curl_init($this->url . '/api/index.php');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($fields),
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

        if ($errno) throw new RuntimeException('Dhru connection error: ' . $error);
        $json = json_decode((string)$raw, true);
        if (!is_array($json)) throw new RuntimeException('Dhru response bukan JSON. HTTP ' . $status);
        if ($status < 200 || $status >= 300) throw new RuntimeException('Dhru HTTP ' . $status);
        return $json;
    }
}
