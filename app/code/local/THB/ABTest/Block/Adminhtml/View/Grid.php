<?php

class THB_ABTest_Block_Adminhtml_View_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setDefaultSortOrder('id');

        # Hide the pager and filter so we only show a basic grid
        # Also, we're not using a container - the header is covered in the base 
        # view.phtml template.
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('abtest/Variation')->getResourceCollection());
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper('core/data');

        $this->addColumn('name', array(
            'header' => $helper->__('Variation Name'),
            'align'  => 'left',
            'width'  => '125px',
            'index'  => 'name',
        ));

        $this->addColumn('description', array(
            'header' => $helper->__('Description'),
            'align'  => 'left',
            'index'  => 'description',
            'renderer' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Longtext'
        ));

        $this->addColumn('conversion_rate', array(
            'header' => $helper->__('Conversion Rate'),
            'align'  => 'left',
            'width'  => '125px',
            'index'  => 'conversion_rate',
            'getter' => 'getConversionRateAsString'
        ));

        $this->addColumn('conversions', array(
            'header' => $helper->__('Conversions'),
            'align'  => 'left',
            'width'  => '125px',
            'index'  => 'conversions'
        ));

        $this->addColumn('visitors', array(
            'header' => $helper->__('Visitors'),
            'align'  => 'left',
            'width'  => '125px',
            'index'  => 'visitors'
        ));

        $this->addColumn('views', array(
            'header' => $helper->__('Views'),
            'align'  => 'left',
            'width'  => '125px',
            'index'  => 'views'
        ));

        $this->addColumn('preview', array(
            'header' => $helper->__('Preview'),
            'align'  => 'center',
            'width'  => '75px',
            'renderer' => 'THB_ABTest_Block_Adminhtml_View_PreviewColumn',
        ));


    }
}
