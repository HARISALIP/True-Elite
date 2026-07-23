-- -----------------------------------------------------------------------------
-- True Elite ERP - Inventory Module Database Migration
-- This script is completely idempotent and safe to run multiple times.
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- 1. ADD NEW COLUMNS TO EXISTING `products` TABLE SAFELY
-- -----------------------------------------------------------------------------

DELIMITER $$

CREATE PROCEDURE AddColumnIfNotExists(
    IN dbName VARCHAR(255),
    IN tableName VARCHAR(255),
    IN columnName VARCHAR(255),
    IN columnDefinition VARCHAR(255)
)
BEGIN
    DECLARE count INT;
    SELECT COUNT(*) INTO count
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = dbName
      AND TABLE_NAME = tableName
      AND COLUMN_NAME = columnName;
      
    IF count = 0 THEN
        SET @query = CONCAT('ALTER TABLE `', tableName, '` ADD COLUMN `', columnName, '` ', columnDefinition);
        PREPARE stmt FROM @query;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END $$

DELIMITER ;

-- Determine current database name automatically
SET @db_name = DATABASE();

-- Call the procedure to add missing columns to products
CALL AddColumnIfNotExists(@db_name, 'products', 'item_code', 'VARCHAR(100) DEFAULT NULL AFTER internal_reference');
CALL AddColumnIfNotExists(@db_name, 'products', 'supplier_id', 'INT(11) DEFAULT NULL AFTER item_code');
CALL AddColumnIfNotExists(@db_name, 'products', 'supplier_name', 'VARCHAR(150) DEFAULT NULL AFTER supplier_id');
CALL AddColumnIfNotExists(@db_name, 'products', 'quantity_in_stock', 'DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER supplier_name');
CALL AddColumnIfNotExists(@db_name, 'products', 'min_stock_alert', 'DECIMAL(10,2) NOT NULL DEFAULT 5.00 AFTER quantity_in_stock');
CALL AddColumnIfNotExists(@db_name, 'products', 'stock_in_total', 'DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER min_stock_alert');
CALL AddColumnIfNotExists(@db_name, 'products', 'stock_out_total', 'DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER stock_in_total');
CALL AddColumnIfNotExists(@db_name, 'products', 'equipment_serial_number', 'VARCHAR(100) DEFAULT NULL AFTER stock_out_total');

-- Clean up the temporary procedure
DROP PROCEDURE IF EXISTS AddColumnIfNotExists;

-- -----------------------------------------------------------------------------
-- 2. DATA MIGRATION: INITIALIZE NEW INVENTORY FIELDS
-- -----------------------------------------------------------------------------

UPDATE `products`
SET
    quantity_in_stock = 0.00,
    min_stock_alert = 5.00,
    stock_in_total = 0.00,
    stock_out_total = 0.00
WHERE quantity_in_stock IS NULL OR min_stock_alert IS NULL;

-- -----------------------------------------------------------------------------
-- 3. CREATE NEW INVENTORY / PURCHASE TABLES
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(150) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text,
  `trn` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `direct_purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_number` varchar(50) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `supplier_name` varchar(150) DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `payment_terms` varchar(100) DEFAULT 'Immediate',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `voucher_number` (`voucher_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `direct_purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `item_code` varchar(100) DEFAULT NULL,
  `product_name` varchar(150) NOT NULL,
  `description` text,
  `category` varchar(100) DEFAULT 'General',
  `quantity` decimal(10,2) NOT NULL DEFAULT '1.00',
  `purchase_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `equipment_serial_number` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  CONSTRAINT `fk_dpi_purchase` FOREIGN KEY (`purchase_id`) REFERENCES `direct_purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `movement_type` enum('IN','OUT') NOT NULL,
  `source_type` enum('DIRECT_PURCHASE','PO','SALES_ORDER','ADJUSTMENT') NOT NULL,
  `source_id` int(11) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_sm_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- 4. SAMPLE POST-MIGRATION INSERT FOR `products`
-- -----------------------------------------------------------------------------
/*
INSERT INTO `products` (
    `product_name`, `description`, `category`, `brand`, `dimension`, `model`, 
    `barcode`, `internal_reference`, `item_code`, `supplier_id`, `supplier_name`, 
    `quantity_in_stock`, `min_stock_alert`, `stock_in_total`, `stock_out_total`, 
    `equipment_serial_number`, `sales_price`, `cost`, `tax`, `image`, 
    `can_be_sold`, `can_be_purchased`, `product_type`, `invoicing_policy`, `internal_notes`
) VALUES (
    'Commercial Gas Range', '4 Burner Gas Range with Oven', 'Kitchen Equipment', 'True Elite', '800x900x850mm', 'TE-GR-4B', 
    '123456789', 'REF-001', 'ITEM-4B-01', 1, 'Main Supplier LLC', 
    10.00, 5.00, 10.00, 0.00, 
    'SN1234567', 1500.00, 1000.00, 5.00, 'uploads/products/gas-range.png', 
    1, 1, 'Consumable', 'Ordered quantities', 'Check gas line requirements before sale'
);
*/
