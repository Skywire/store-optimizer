<?php

/**
 * Integrates our A/B tests with Google Analytics
 *
 */
class THB_ABTest_Block_Analytics extends Mage_Core_Block_Text
{

    public function _toHtml()
    {
        $store_uenc = Mage::getStoreConfig('abtest/settings/store_uenc');

        # Don't output any Google Analytics tags if either the Google Analytics 
        # plugin hasn't been set up or the settings tell us not to.
        if (Mage::getStoreConfig('google/analytics/active') == 0 OR Mage::getStoreConfig('abtest/settings/analytics') == 0)
            return "<script>var store_uenc = '$store_uenc';</script>";

        if (Mage::helper('abtest')->isRunning())
        {
            $custom_variable_slot = Mage::getStoreConfig('abtest/settings/variable_slot');
            if ( ! $custom_variable_slot) {
                $custom_variable_slot = 5;
            }

            $variations = Mage::helper('abtest/visitor')->getAllVariations();
        }
        else
        {
            $variations = array();
        }

        $output = "var store_uenc = '$store_uenc';\r\n";
        foreach ($variations as $variation) {
            if (isset($variation['test']['name']) && isset($variation['variation']['name'])) {
                $output .= "_gaq.push(['_setCustomVar', {$custom_variable_slot}, '{$variation['test']['name']}', '{$variation['variation']['name']}', 2]);\r\n";
            }
        }

        return "<script>\r\n".$output."</script>";
    }

}
