<?php
class MW_Ipchecker_Model_Observer
{
  public function customObserverAction(Varien_Event_Observer $observer)
  {
    $object = $observer->getEvent()->getObject(); // we are taking the item with 'object' key from array passed to dispatcher
    die;

    return $this;
}
?>