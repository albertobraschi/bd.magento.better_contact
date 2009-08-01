<?php
/**
 * @category   BD
 * @package    BD_BetterContact
 * @author     Tristan Blease <tristan@bleasedesign.com>
 * @copyright  Copyright (c) 2009 Blease Design LLC (http://bleasedesign.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
INSERT INTO {$this->getTable('core_email_template')} (`template_code`, `template_text`, `template_type`, `template_subject`, `template_sender_name`, `template_sender_email`, `added_at`, `modified_at`) VALUES
('BD BetterContact Form (Plain)', 'Name: {{var data.name}}\r\nTitle: {{var data.title}}\r\nE-mail: {{var data.email}}\r\nTelephone: {{var data.telephone}}\r\nCompany: {{var data.company}}\r\nWebsite URL: {{var data.website}}\r\n\r\nComment: {{var data.comment}}', 1, 'BD BetterContact Form', NULL, NULL, NOW(), NOW());
");
$installer->endSetup();
