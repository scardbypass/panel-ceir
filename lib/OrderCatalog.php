<?php
declare(strict_types=1);

final class OrderCatalog
{
    public static function schema(?string $json): array
    {
        if (!$json) return [];
        $data = json_decode($json, true);
        if (!is_array($data)) return [];
        $fields = [];
        foreach ($data as $field) {
            if (!is_array($field)) continue;
            $name = trim((string)($field['name'] ?? ''));
            if ($name === '' || !preg_match('/^[A-Za-z][A-Za-z0-9_\-]*$/', $name)) continue;
            $type = strtolower((string)($field['type'] ?? 'text'));
            if (!in_array($type, ['text','number','tel','email','textarea','select'], true)) $type = 'text';
            $options = [];
            if ($type === 'select' && isset($field['options']) && is_array($field['options'])) {
                foreach ($field['options'] as $option) {
                    $option = trim((string)$option);
                    if ($option !== '') $options[] = $option;
                }
            }
            $fields[] = [
                'name' => $name,
                'label' => trim((string)($field['label'] ?? $name)),
                'type' => $type,
                'required' => !empty($field['required']),
                'placeholder' => trim((string)($field['placeholder'] ?? '')),
                'min' => isset($field['min']) ? (int)$field['min'] : null,
                'max' => isset($field['max']) ? (int)$field['max'] : null,
                'options' => $options,
            ];
        }
        return $fields;
    }

    public static function defaultsForProduct(string $name, string $type = ''): array
    {
        $text = strtolower($name . ' ' . $type);
        if (str_contains($text, 'imei') || str_contains($text, 'ceir') || str_contains($text, 'apple')) {
            return [[
                'name' => 'imei', 'label' => 'Nomor IMEI', 'type' => 'tel', 'required' => true,
                'placeholder' => 'Masukkan 15 digit IMEI', 'min' => 14, 'max' => 16, 'options' => []
            ]];
        }
        return [[
            'name' => 'target', 'label' => 'Target', 'type' => 'text', 'required' => true,
            'placeholder' => 'Masukkan target', 'min' => null, 'max' => null, 'options' => []
        ]];
    }

    public static function menu(mysqli $db): array
    {
        $items = [];
        $sql = "SELECT m.service_id,m.label,m.icon,m.group_name,m.sort_order,l.layanan,l.harga,l.status,l.provider_id,l.provider,l.image_url
                FROM order_menu m JOIN layanan_digital l ON l.provider_id=m.service_id
                WHERE m.is_visible=1 AND l.status='Normal' AND COALESCE(l.public_visible,1)=1
                ORDER BY m.group_name ASC,m.sort_order ASC,m.id ASC";
        $q = $db->query($sql);
        if ($q) while ($row = $q->fetch_assoc()) $items[] = $row;
        return $items;
    }

    public static function service(mysqli $db, string $serviceId): ?array
    {
        $stmt = $db->prepare("SELECT * FROM layanan_digital WHERE provider_id=? AND status='Normal' AND COALESCE(public_visible,1)=1 LIMIT 1");
        $stmt->bind_param('s', $serviceId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $row;
    }
}
