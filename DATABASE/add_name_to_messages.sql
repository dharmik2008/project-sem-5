-- Add name field to messages table for contact form
-- Run this SQL to update your existing messages table

ALTER TABLE `messages` ADD COLUMN `name` VARCHAR(100) AFTER `user_id`;

-- Update existing messages to have a default name if needed
UPDATE `messages` SET `name` = 'Guest User' WHERE `name` IS NULL OR `name` = '';

-- Make the name field NOT NULL after updating existing records
ALTER TABLE `messages` MODIFY COLUMN `name` VARCHAR(100) NOT NULL;
