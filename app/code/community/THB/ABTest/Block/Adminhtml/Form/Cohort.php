<?php

class THB_ABTest_Block_Adminhtml_Form_Cohort extends Mage_Adminhtml_Block_Template
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('abtest/form/cohort.phtml');

        $this->_data['config_data'] = Mage::getModel("adminhtml/config_data")
            ->setSection("design")
            ->setWebsite("")
            ->setStore("")
            ->load();
    }

}
