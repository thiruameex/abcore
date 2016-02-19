<?php
class MW_Cmspro_Model_Source_Fieldsdeeplevel
{
    public function toOptionArray()
    {
        return array(
			array('value' => 0, 'label' => 'All'),
            array('value' => 1, 'label' => 'Level 1'),           
			array('value' => 2, 'label' => 'Level 2'),
            array('value' => 3, 'label' => 'Level 3'),
			array('value' => 4, 'label' => 'Level 4'),
			array('value' => 5, 'label' => 'Level 5')
		);
    }
}