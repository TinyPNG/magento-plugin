<?php
class Tiny_CompressImages_Block_Adminhtml_System_Config_Form_Field_Api extends Varien_Data_Form_Element_Abstract
{
    /**
     * Generate the status for the TinyPNG extension.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $_helper = Mage::helper('tig_tinypng');
        $apiStatusCache = Mage::app()->loadCache('tig_tinypng_api_status');
        $message = $_helper->__('Click the button to check the API status.');

        if ($apiStatusCache !== false) {
            $apiStatusCacheData = json_decode($apiStatusCache, true);

            switch ($apiStatusCacheData['status']) {
                case 'operational':
                    $message = '<span class="tinypng_status_success"><span class="apisuccess"></span>'
                        . Mage::helper('tig_tinypng')->__('API connection successful')
                        . '</span>';
                    break;
                case 'nonoperational':
                    $message = '<span class="tinypng_status_failure">'
                        . Mage::helper('tig_tinypng')->__('Non-operational')
                        . '</span>';
                    break;
            }
        }

        $message = '<span id="tinypng_api_status">' . $message . '</span><br>';

        return $message;
    }

    /**
     * @return string
     */
    public function getScopeLabel()
    {
        $_helper = Mage::helper('tig_tinypng');

        $js = '<script type="text/javascript">
                    var url = "' . Mage::helper("adminhtml")->getUrl('adminhtml/tinypngAdminhtml_status/getApiStatus') . '";

                    document.observe("dom:loaded", function() {
                        new Ajax.Request(url,
                            {
                                method: "post",
                                onSuccess: function (data) {
                                    var result = data.responseText.evalJSON(true);
                                    if (result.status == "success") {
                                        $("tinypng_api_status").innerHTML = result.message;
                                    }
                                }
                            })
                    });
                </script>';

        $label = parent::getScopeLabel() . $js;

        return $label;
    }
}
