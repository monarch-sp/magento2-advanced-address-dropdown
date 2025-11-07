<?php
namespace SP\Fourleveladdress\Plugin\Block\Checkout;
use Magento\Checkout\Block\Checkout\LayoutProcessor;
class LayoutProcessorPlugin
{
     public function afterProcess(
        LayoutProcessor $subject,
        array $jsLayout
    ) {
        $cityRenderOptions = $this->prepareCityRenderOptions();
        $jsLayout = $this->addShippingAddressDistrictIdField($jsLayout, $cityRenderOptions);
        $jsLayout = $this->addShippingAddressCityVisibility($jsLayout);



        return $jsLayout;
    }

    private function addShippingAddressDistrictIdField($jsLayout, $renderOptions = [])
    {
        $districtIdField = [
            'component' => 'SP_Fourleveladdress/js/form/element/district',
            'config'    => [
                'customScope'   => 'shippingAddress.custom_attributes',
                'customEntry'   => 'shippingAddress.district',
                'template'      => 'ui/form/field',
                'elementTmpl'   => 'ui/form/element/select',
            ],
            'label' => __('District'),
            //'value' => '',
            'dataScope' => 'shippingAddress.custom_attributes.district_id',
            'provider' => 'checkoutProvider',
            'sortOrder' => $renderOptions['sortOrder'],
            'customEntry' => null,
            'visible' => true,
            'options' => [],
            'validation' => [
                'required-entry' => true,
            ],
            'filterBy'  => [
                'target' => '${ $.provider }:shippingAddress.region_id',
                'field'  => 'region_id'
            ],
            'imports'   => [
                'initialOptions'    => 'index = checkoutProvider:dictionaries.district_id',
                'setOptions'        => 'index = checkoutProvider:dictionaries.district_id'
            ]
        ];




        $dcityIdField = [
            'component' => 'SP_Fourleveladdress/js/form/element/city',
            'config'    => [
                'customScope'   => 'shippingAddress.custom_attributes',
                'customEntry'   => 'shippingAddress.dcity',
                'template'      => 'ui/form/field',
                'elementTmpl'   => 'ui/form/element/select',
            ],
            'label' => __('District city'),
            //'value' => '',
            'dataScope' => 'shippingAddress.custom_attributes.dcity_id',
            'provider' => 'checkoutProvider',
            'sortOrder' => 95,
            'customEntry' => null,
            'visible' => true,
            'options' => [],
            'validation' => [
                'required-entry' => true,
            ],
            'filterBy'  => [
                'target' => '${ $.provider }:shippingAddress.district_id',
                'field'  => 'district_id'
            ],
            'imports'   => [
                'initialOptions'    => 'index = checkoutProvider:dictionaries.dcity_id',
                'setOptions'        => 'index = checkoutProvider:dictionaries.dcity_id'
            ]
        ];


        $jsLayout['components']['checkout']['children']['steps']['children']
        ['shipping-step']['children']['shippingAddress']['children']
        ['shipping-address-fieldset']['children']['dcity_id'] = $dcityIdField;

         $jsLayout['components']['checkout']['children']['steps']['children']
        ['shipping-step']['children']['shippingAddress']['children']
        ['shipping-address-fieldset']['children']['district_id'] = $districtIdField;



        return $jsLayout;
    }

    private function addShippingAddressCityVisibility($jsLayout)
    {

            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']
            ['children']['city']['visible'] = false;

        return $jsLayout;
    }


    private function prepareCityRenderOptions()
    {
        return [
            'sortOrder' => 91,
            'searchable' => 1
        ];
    }

    private function getCityIdSortOrder()
    {
        return 91;
    }
}
