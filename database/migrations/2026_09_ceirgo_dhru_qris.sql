-- Provider integration tables for the new API layer.
-- Safe to run once on an existing installation.

CREATE TABLE IF NOT EXISTS provider_accounts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  provider_code VARCHAR(50) NOT NULL,
  name VARCHAR(100) NOT NULL,
  base_url VARCHAR(255) NOT NULL,
  username VARCHAR(150) NULL,
  api_key TEXT NULL,
  api_secret TEXT NULL,
  webhook_secret TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  last_sync_at DATETIME NULL,
  last_error TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_provider_account (provider_code, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS provider_services (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  account_id INT UNSIGNED NOT NULL,
  external_id VARCHAR(100) NOT NULL,
  code VARCHAR(150) NULL,
  category VARCHAR(150) NULL,
  name VARCHAR(255) NOT NULL,
  cost DECIMAL(18,2) NOT NULL DEFAULT 0,
  selling_price DECIMAL(18,2) NOT NULL DEFAULT 0,
  status VARCHAR(30) NOT NULL DEFAULT 'active',
  raw_json LONGTEXT NULL,
  synced_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_provider_service (account_id, external_id),
  KEY idx_provider_service_category (account_id, category),
  CONSTRAINT fk_provider_services_account FOREIGN KEY (account_id) REFERENCES provider_accounts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS provider_orders (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  account_id INT UNSIGNED NOT NULL,
  user_id INT NULL,
  local_order_id VARCHAR(80) NOT NULL,
  external_order_id VARCHAR(100) NULL,
  service_id BIGINT UNSIGNED NULL,
  target VARCHAR(255) NOT NULL,
  amount DECIMAL(18,2) NOT NULL DEFAULT 0,
  provider_cost DECIMAL(18,2) NOT NULL DEFAULT 0,
  status VARCHAR(30) NOT NULL DEFAULT 'pending',
  result TEXT NULL,
  last_provider_status VARCHAR(50) NULL,
  raw_json LONGTEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_provider_local_order (local_order_id),
  KEY idx_provider_external_order (account_id, external_order_id),
  KEY idx_provider_order_status (status),
  CONSTRAINT fk_provider_orders_account FOREIGN KEY (account_id) REFERENCES provider_accounts(id) ON DELETE CASCADE,
  CONSTRAINT fk_provider_orders_service FOREIGN KEY (service_id) REFERENCES provider_services(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payment_transactions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  invoice VARCHAR(100) NOT NULL,
  provider VARCHAR(50) NOT NULL,
  external_id VARCHAR(150) NULL,
  amount DECIMAL(18,2) NOT NULL,
  paid_amount DECIMAL(18,2) NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'pending',
  qr_string TEXT NULL,
  qr_image TEXT NULL,
  expires_at DATETIME NULL,
  callback_at DATETIME NULL,
  raw_json LONGTEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_payment_invoice (invoice),
  KEY idx_payment_external (provider, external_id),
  KEY idx_payment_user_status (user_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
