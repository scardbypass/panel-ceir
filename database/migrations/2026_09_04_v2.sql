-- Panel CEIR V2: additive migration. Existing tables/routes remain compatible.
START TRANSACTION;

ALTER TABLE layanan_digital
  ADD COLUMN IF NOT EXISTS public_visible TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS sort_order INT NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS image_url VARCHAR(500) NULL,
  ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL;

CREATE TABLE IF NOT EXISTS api_clients (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  api_key_hash CHAR(64) NOT NULL,
  label VARCHAR(100) NOT NULL DEFAULT 'Default',
  status ENUM('active','disabled') NOT NULL DEFAULT 'active',
  last_used_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_api_key_hash (api_key_hash),
  KEY idx_api_user (user_id),
  CONSTRAINT fk_api_clients_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payment_transactions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  invoice_id VARCHAR(150) NOT NULL,
  username VARCHAR(100) NOT NULL,
  amount BIGINT UNSIGNED NOT NULL,
  final_amount BIGINT UNSIGNED NOT NULL,
  provider VARCHAR(50) NOT NULL,
  status ENUM('pending','paid','expired','cancelled','failed') NOT NULL DEFAULT 'pending',
  provider_reference VARCHAR(150) NULL,
  raw_response LONGTEXT NULL,
  paid_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_payment_invoice (invoice_id),
  KEY idx_payment_user_status (username,status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS provider_orders_v2 (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  local_order_id VARCHAR(80) NOT NULL,
  provider VARCHAR(50) NOT NULL,
  provider_order_id VARCHAR(150) NULL,
  user_id INT NOT NULL,
  service_id VARCHAR(80) NOT NULL,
  target VARCHAR(255) NOT NULL,
  cost BIGINT UNSIGNED NOT NULL DEFAULT 0,
  sell_price BIGINT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('pending','processing','success','failed','refunded') NOT NULL DEFAULT 'pending',
  response_message TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_local_order (local_order_id),
  KEY idx_provider_order (provider,provider_order_id),
  KEY idx_provider_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
