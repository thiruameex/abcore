<?php
/**
 * Catalog Observer
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class MW_Uploadattribute_Model_Observer
{
    public function saveFile($observer)
    {
    		$pId = $observer->getProduct()->getId();
    		//var_dump($pId);die;
    		//upload MSDS
        	if(isset($_FILES['msds_file']['name']) && $_FILES['msds_file']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('msds_file');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('pdf'));
					$uploader->setAllowRenameFiles(true);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(true);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media').DS.'msds' . DS ;
					$uploader->save($path, $_FILES['msds_file']['name'] );
					$path = $uploader->getDispretionPath($_FILES['msds_file']['name']);
					$file = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'msds'.$uploader->getUploadedFileName();
					//this way the name is saved in DB
	  				$observer->getProduct()->setMsds($file);
				} catch (Exception $e) {
		      		Mage::getSingleton('adminhtml/session')->addError('MSDS file upload error. '.$e->getMessage());
		        }
			}
			//upload manual
  			if(isset($_FILES['manual_file']['name']) && $_FILES['manual_file']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('manual_file');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('pdf'));
					$uploader->setAllowRenameFiles(true);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(true);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media').DS.'manual' . DS ;					
					$uploader->save($path, $_FILES['manual_file']['name'] );
					$path = $uploader->getDispretionPath($_FILES['manual_file']['name']);
					$file = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'manual'.$uploader->getUploadedFileName();
					//this way the name is saved in DB
					$observer->getProduct()->setManual($file);
				} catch (Exception $e) {
		      		Mage::getSingleton('adminhtml/session')->addError('Manual file upload error. '.$e->getMessage());
		        }
			}
			return $this;
    }
    
}
