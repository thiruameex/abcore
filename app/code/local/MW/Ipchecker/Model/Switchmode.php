<?php 
class MW_Ipchecker_Model_Switchmode
{
    protected $_options;

    public function toOptionArray($isMultiselect=null)
    {	
	$option=array();
	$a1=array('value'=>'3','label'=>'Switch both Currency & Language automatically');$options[]=$a1;
	$a2=array('value'=>'1','label'=>'Only Switch Currency automatically');$options[]=$a2;
    $a3=array('value'=>'2','label'=>'Only Switch Language automatically');$options[]=$a3; 
	  return $options;
    }
}
