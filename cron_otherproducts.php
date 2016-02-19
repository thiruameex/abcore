<?php
require_once('app/Mage.php'); //Path to Magento
umask(0);
Mage::app();
Mage::getModel('otherproducts/otherproducts')->saveintable();
?>