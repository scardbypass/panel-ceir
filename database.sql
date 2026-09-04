-- Panel CEIR V2 database bootstrap.
--
-- IMPORTANT:
-- This file is intentionally kept as a bootstrap for a fresh V2 install.
-- Existing/legacy installations should run database/migrations/2026_09_04_v2.sql
-- because the legacy application still contains active compatibility routes.
--
-- The canonical legacy dump is preserved in git history; V2-specific schema is
-- maintained separately so the database definition does not drift.

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS wallet_ledger (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  username VARCHAR(100) NOT NULL,
  direction ENUM('credit','debit') NOT NULL,
  amount BIGINT UNSIGNED NOT NULL,
  balance_before BIGINT UNSIGNED NOT NULL,
  balance_after BIGINT UNSIGNED NOT NULL,
  reference_key VARCHAR(150) NOT NULL,
  action VARCHAR(60) NOT NULL,
  message VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_wallet_reference (reference_key),
  KEY idx_wallet_user_created (user_id, created_at),
  KEY idx_wallet_username_created (username, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  KEY idx_api_user (user_id)
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
  KEY idx_payment_user_status (username, status)
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
  KEY idx_provider_order (provider, provider_order_id),
  KEY idx_provider_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
