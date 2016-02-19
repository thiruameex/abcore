<?php
class MW_SmartMenu_Model_Source_Fieldsleft
{
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => 'Yes, Accordion'),
           
			array('value' => 2, 'label' => 'Yes, Drop down'),
           
			array('value' => 0, 'label' => 'No')
		);
    }
}