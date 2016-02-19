<?php 
class MW_Ipchecker_Model_Currency extends Mage_Adminhtml_Model_System_Config_Source_Currency
{
    protected $_options;

    public function toOptionArray($isMultiselect)
    {	
		$allow=array();
        if (!$this->_options) {
           $this->_options = Mage::app()->getLocale()->getOptionCurrencies();
		   $allow=explode(",",Mage::getStoreConfig('currency/options/allow'));
        }
		$ar1=array('value'=>null,'label'=>"--Please Select--");	
		$options=array();$options[]=$ar1;
		foreach($this->_options as $option)
		{     
			if(in_array($option['value'],$allow))
				$options[] = $option;		
				
		}
        return $options;
    }
}
