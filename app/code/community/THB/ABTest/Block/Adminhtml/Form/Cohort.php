<?php

class THB_ABTest_Block_Adminhtml_Form_Cohort extends Mage_Adminhtml_Block_Widget_Form
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

        $this->_fieldsetRenderer = Mage::getBlockSingleton("adminhtml/widget_form_renderer_fieldset");
    }

    public function initForm()
    {
        $this->_form = new Varien_Data_Form();

        # $this->_fieldsetRenderer->setForm($this->_form);

        $this->_addNameFieldset()
            ->_addXmlFieldset()
            ->_addThemeFieldset();

        return $this->_form->toHtml();
    }

    protected function _addNameFieldset()
    {
        $baseName = "cohort_".$this->getCohort()."_name";

        # This works exactly the same as having no renderer...
        $fieldset = $this->_form
            ->addFieldset( "$baseName-fieldset", array(
                "legend"   => $this->__("Name"),
            ))
            ->setRenderer($this->_fieldsetRenderer);

        if ($this->getCohort() == "Control") {
            $value = "Control";
        } else {
            $value = "Variation ".$this->getCohort();
        }

        $fieldset->addField("$baseName-field", "text", array(
            "label"    => $this->__("Variation name"),
            "required" => true,
            "value"    => $value,
            "name"     => "cohort[".$this->getCohort()."][name]",
        ));

        return $this;
    }

    protected function _addXmlFieldset()
    {
        $fieldset = $this->_form->addFieldset("cohort_".$this->getCohort()."_xml-fieldset", array(
            "legend"      => $this->__("XML Layout Updates"),
            "table_class" => "form-edit"
        ));

        $fieldset->addField("cohort_".$this->getCohort()."_xml-field", "textarea", array(
            "name"     => "cohort[".$this->getCohort()."][layout_update]",
            "style"    => "width: 97.5%; height: 25em; margin: 5px; font-family: monospace",
        ));

        return $this;
    }

    protected function _addThemeFieldset()
    {
        $regex_renderer = Mage::getBlockSingleton("adminhtml/system_config_form_field_regexceptions");
        $regex_renderer->setForm($this->_form);
        $fieldset = $this->_form->addFieldset("cohort_".$this->getCohort()."_theme", array(
            "legend"   => $this->__("Theme Updates (leave these blank for config defaults)"),
        ));

        # Uset when getting the data on a view form
        # $model = Mage::getModel("adminhtml/system_config_backend_serialized_array");

        $fieldset->addField("cohort_".$this->getCohort()."_package-field", "text", array(
            "label"    => $this->__("Package name"),
            "name"     => "cohort[".$this->getCohort()."][package][name]",
        ));
        $field = $fieldset->addField("cohort_".$this->getCohort()."_package_exceptions-field", "text", array(
            "name"     => "cohort[".$this->getCohort()."][package][exceptions]",
            "comment"  => $this->__("Match expressions in the same order as displayed in the configuration."),
        ));
        $field->setRenderer($regex_renderer);

        $fieldset->addField("cohort_".$this->getCohort()."_templates-field", "text", array(
            "label"    => $this->__("Templates"),
            "name"     => "cohort[".$this->getCohort()."][package][exceptions]",
        ));
        $field = $fieldset->addField("cohort_".$this->getCohort()."_templates_exceptions-field", "text", array(
            "name"     => "cohort[".$this->getCohort()."][templates][exceptions]",
        ));
        $field->setRenderer($regex_renderer);

        $fieldset->addField("cohort_".$this->getCohort()."_skin-field", "text", array(
            "label"    => $this->__("Skin (Images / CSS)"),
            "name"     => "cohort[".$this->getCohort()."][package][exceptions]",
        ));
        $field = $fieldset->addField("cohort_".$this->getCohort()."_skin_exceptions-field", "text", array(
            "name"     => "cohort[".$this->getCohort()."][skin][exceptions]",
        ));
        $field->setRenderer($regex_renderer);

        $fieldset->addField("cohort_".$this->getCohort()."_layout-field", "text", array(
            "label"    => $this->__("Layout"),
            "name"     => "cohort[".$this->getCohort()."][package][exceptions]",
        ));
        $field = $fieldset->addField("cohort_".$this->getCohort()."_layout_exceptions-field", "text", array(
            "name"     => "cohort[".$this->getCohort()."][layout][exceptions]",
        ));
        $field->setRenderer($regex_renderer);

        $fieldset->addField("cohort_".$this->getCohort()."_default-field", "text", array(
            "label"    => $this->__("Default"),
            "name"     => "cohort[".$this->getCohort()."][package][exceptions]",
        ));
        $field = $fieldset->addField("cohort_".$this->getCohort()."_default_exceptions-field", "text", array(
            "name"     => "cohort[".$this->getCohort()."][default][exceptions]",
        ));
        $field->setRenderer($regex_renderer);

        return $this;
    }

}
