-- Panel CEIR foundation migration.
-- Run after V2/V3/DHRU migrations.
-- Adds durable audit/idempotency metadata without changing legacy order tables.

CREATE TABLE IF NOT EXISTS order_events (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  local_order_id VARCHAR(80) NOT NULL,
  status_from VARCHAR(30) NULL,
  status_to VARCHAR(30) NOT NULL,
  actor VARCHAR(100) NULL,
  message VARCHAR(500) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_order_events_order_created (local_order_id, created_at),
  KEY idx_order_events_status (status_to, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS schema_migrations (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  migration_key VARCHAR(150) NOT NULL,
  applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_schema_migration (migration_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO schema_migrations (migration_key) VALUES ('2026_09_05_foundation');
