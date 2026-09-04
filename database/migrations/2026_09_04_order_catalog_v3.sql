-- Panel CEIR V3: dynamic order catalog + product form builder
-- Run after 2026_09_04_v2.sql

ALTER TABLE layanan_digital
  ADD COLUMN IF NOT EXISTS public_visible TINYINT(1) NOT NULL DEFAULT 1,
  ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS image_url VARCHAR(500) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS order_form_json LONGTEXT DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS menu_label VARCHAR(150) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS menu_icon VARCHAR(80) DEFAULT 'mdi mdi-cart-outline';

CREATE TABLE IF NOT EXISTS order_menu (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  service_id VARCHAR(50) NOT NULL,
  label VARCHAR(150) NOT NULL,
  icon VARCHAR(80) NOT NULL DEFAULT 'mdi mdi-cart-outline',
  group_name VARCHAR(100) NOT NULL DEFAULT 'Menu Utama',
  sort_order INT NOT NULL DEFAULT 0,
  is_visible TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_order_menu_service (service_id),
  KEY idx_order_menu_visible (is_visible, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Existing products remain available. Admin controls what appears in the member menu.
