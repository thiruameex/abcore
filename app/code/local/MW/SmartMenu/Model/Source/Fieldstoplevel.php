<?php
class MW_SmartMenu_Model_Source_Fieldstoplevel
{
    public function toOptionArray()
    {
        return array(
			
            array('value' => 0, 'label' => 'Level 1'),           
			array('value' => 1, 'label' => 'Level 2')
		);
    }
}