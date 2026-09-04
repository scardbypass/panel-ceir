-- DHRU compatibility additions for the legacy provider/catalog schema.
-- Safe to run after the existing database dump and V2/V3 migrations.

ALTER TABLE provider
  ADD COLUMN IF NOT EXISTS status VARCHAR(20) NOT NULL DEFAULT 'active',
  ADD COLUMN IF NOT EXISTS updated_at DATETIME NULL;

ALTER TABLE layanan_digital
  ADD COLUMN IF NOT EXISTS service_id VARCHAR(80) NULL,
  ADD COLUMN IF NOT EXISTS dhru_group VARCHAR(100) NULL;

-- Backfill the DHRU service identifier from the existing provider identifier.
UPDATE layanan_digital
SET service_id = provider_id
WHERE (service_id IS NULL OR service_id = '')
  AND provider_id IS NOT NULL
  AND provider_id <> '';

UPDATE layanan_digital
SET dhru_group = operator
WHERE (dhru_group IS NULL OR dhru_group = '')
  AND operator IS NOT NULL
  AND operator <> '';

CREATE INDEX IF NOT EXISTS idx_layanan_dhru_service ON layanan_digital(service_id);
CREATE INDEX IF NOT EXISTS idx_layanan_dhru_provider ON layanan_digital(provider,status);
