<?php
class MW_Cmspro_Block_Comment_List extends Mage_Review_Block_Product_View
{
    protected $_forceHasOptions = false;
	protected $_defaultToolbarBlock = 'cmspro/page_pager';

    public function getProductId()
    {
        $param = $this->getRequest()->getParam('id');
		$id  =   Mage::getModel('cmspro/news')->load($param)->getId();
        return $id;
    }
	   public function getItem()
    {
        $param = $this->getRequest()->getParam('id');
		$item  =   Mage::getModel('cmspro/news')->load($param);
        return $item;
    }
	public function getCommentCollection(){
	$new_id =  $this->getRequest()->getParam('id');
		$collection = Mage::getModel('cmspro/comment')
				->getCollection()
				->addFieldToFilter('status', 1)
				->addFieldToFilter('news_id', $new_id)
				->setOrder('created_time', 'desc');
		;
		
		$currentPage = (int)$this->getRequest()->getParam('page');
	    if(!$currentPage){
	        $currentPage = 1;
	    }
	    
	    $currentLimit = (int)$this->getRequest()->getParam('limit');
	    if(!$currentLimit){
			$limit = Mage::getStoreConfig('mw_cmspro/comment/per_page');
	        $currentLimit = $limit ? $limit : 5;
	    }
	    
	    $collection->setPageSize($currentLimit);
	    $collection->setCurPage($currentPage);
		$collection->load();
		return $collection;
	}

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($toolbar = $this->getLayout()->getBlock('product_review_list.toolbar')) {
            $toolbar->setCollection($this->getCommentCollection());
            $this->setChild('toolbar', $toolbar);
        }

        return $this;
    }

    protected function _beforeToHtml()
    {
	  $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->getCommentCollection();
        
        // set collection to tollbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        
        $this->getCommentCollection()->load();
        return parent::_beforeToHtml();
    }
	public function getToolbarBlock()
    {
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }
}
