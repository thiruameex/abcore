<?php

class MW_EmailOrderForForeign_Block_Adminhtml_Distributor_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('distributorGrid');
        $this->setDefaultSort('distributor_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('emailorderforforeign/distributor')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('distributor_id', array(
            'header' => Mage::helper('emailorderforforeign')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'distributor_id',
        ));
        $countries = Mage::getSingleton('emailorderforforeign/distributor')->getCountryOptionArray();
        $this->addColumn('country', array(
            'header' => Mage::helper('emailorderforforeign')->__('Country'),
            'align' => 'left',
            'index' => 'country',
            'width' => '200px',
            'type'  => 'options',
            'options' => $countries,
            'filter' => false,
            'sortable' => false
        ));
        
        $distributorLinks = Mage::getSingleton('emailorderforforeign/distributor')->getDistributorLinkOptionArray();
        $this->addColumn('distributor_link', array(
            'header' => Mage::helper('emailorderforforeign')->__('Ditributor\'s Link'),
            'align' => 'left',
            'width' => '200px',
            'type' => 'options',
            'index' => 'distributor_link',
            'options' => $distributorLinks,
            'filter' => false,
            'sortable' => false
        ));
        
        $this->addColumn('action', array(
            'header' => Mage::helper('emailorderforforeign')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('emailorderforforeign')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'distributor_id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

//        $this->addExportType('*/*/exportCsv', Mage::helper('helpdesk')->__('CSV'));
//        $this->addExportType('*/*/exportXml', Mage::helper('helpdesk')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('distributor_ids');
        $this->getMassactionBlock()->setFormFieldName('distributor_ids');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('emailorderforforeign')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('emailorderforforeign')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('distributor_id' => $row->getId()));
    }

}

?>
