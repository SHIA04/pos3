<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_tbl_orders extends CI_Migration {

    public function up()
    {
        // Add columns required by application
        // Note: these ALTERs assume the table exists and columns do not yet exist.
        $this->db->query("ALTER TABLE `tbl_orders` ADD COLUMN `customer_name` VARCHAR(150) NOT NULL AFTER `staff_id`");
        $this->db->query("ALTER TABLE `tbl_orders` ADD COLUMN `order_items` TEXT AFTER `customer_name`");
        $this->db->query("ALTER TABLE `tbl_orders` ADD COLUMN `image` VARCHAR(255) DEFAULT NULL AFTER `status`");
        $this->db->query("ALTER TABLE `tbl_orders` ADD COLUMN `order_date` DATETIME DEFAULT CURRENT_TIMESTAMP AFTER `image`");

        // Update status enum to include 'Processing' (application uses 'Processing')
        $this->db->query("ALTER TABLE `tbl_orders` MODIFY `status` ENUM('New','Processing','Done') DEFAULT 'New'");
    }

    public function down()
    {
        // Revert changes (drops columns and restore old enum)
        $this->db->query("ALTER TABLE `tbl_orders` DROP COLUMN IF EXISTS `customer_name`");
        $this->db->query("ALTER TABLE `tbl_orders` DROP COLUMN IF EXISTS `order_items`");
        $this->db->query("ALTER TABLE `tbl_orders` DROP COLUMN IF EXISTS `image`");
        $this->db->query("ALTER TABLE `tbl_orders` DROP COLUMN IF EXISTS `order_date`");
        // Restore original enum (includes Pending)
        $this->db->query("ALTER TABLE `tbl_orders` MODIFY `status` ENUM('New','Pending','Done') DEFAULT 'New'");
    }
}
