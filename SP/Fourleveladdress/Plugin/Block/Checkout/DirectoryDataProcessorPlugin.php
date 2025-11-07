<?php
namespace SP\Fourleveladdress\Plugin\Block\Checkout;
use Magento\Checkout\Block\Checkout\DirectoryDataProcessor;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;
class DirectoryDataProcessorPlugin
{
    protected $moduleDir;
    protected $moduleDirReader;

    public function __construct(
        Dir $moduleDir,
        Reader $moduleDirReader
    ) {
        $this->moduleDir = $moduleDir;
        $this->moduleDirReader = $moduleDirReader;
    }

    public function afterProcess(
        DirectoryDataProcessor $subject,
        array $jsLayout
    ) {
        if (isset($jsLayout['components']['checkoutProvider']['dictionaries'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries']['district_id'] = $this->getDistrictOptions();
        }
        if (isset($jsLayout['components']['checkoutProvider']['dictionaries'])) {
            $jsLayout['components']['checkoutProvider']['dictionaries']['dcity_id'] = $this->getCityOptions();
        }
        return $jsLayout;
    }

    private function getCityOptions()
    {
        $options = $this->getStaticCityOptions();
        return $options;
    }

    private function getDistrictOptions()
    {
        $options = $this->getStaticDistrictOptions();
        return $options;
    }

    private function readCsv($fileName)
    {
        $moduleName = 'SP_Fourleveladdress';
        $moduleBasePath = $this->moduleDirReader->getModuleDir('', $moduleName);
        $Path = $this->moduleDir->getDir($moduleName, Dir::MODULE_SETUP_DIR);
        $filePath = BP . '/app/code/SP/Fourleveladdress/Setup/data/' . $fileName;
        if (!file_exists($filePath)) {
        return [];
        }
        $rows = [];
        if (($handle = fopen($filePath, "r")) !== false) {
        $header = fgetcsv($handle); // read first line as header
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($header, $data);
        }
        fclose($handle);
        }
        return $rows;
    }
    private function getStaticDistrictOptions()
    {
        $options = $this->readCsv('districts.csv');

        array_unshift($options, [
            'title' => '',
            'value' => '',
            'label' => __('Please select district.')
        ]);

        return $options;
    }

    private function getStaticCityOptions()
    {
        $options = $this->readCsv('cities.csv');

        array_unshift($options, [
            'title' => '',
            'value' => '',
            'label' => __('Please select a city.')
        ]);

        return $options;
    }



    private function sortByKey(&$data, $key)
    {
        usort($data, function ($a, $b) use ($key) {
            return strcmp($a[$key], $b[$key]);
        });
    }


    public function getDummyCityOptions()
    {
        $options=[
        [
            'title' => '',
            'value' => '',
            'label' => __('Please select a city.')
        ],
        [
            'value'      => 101,
            'title'      => 'Demo City One',
            'country_id' => 'IN',
            'region_id'  => 123,
            'label'      => 'Demo City One'
        ],
        [
            'value'      => 102,
            'title'      => 'Demo City Two',
            'country_id' => 'IN',
            'region_id'  => 123,
            'label'      => 'Demo City Two'
        ],
        [
            'value'      => 103,
            'title'      => 'Demo City Three',
            'country_id' => 'IN',
            'region_id'  => 123,
            'label'      => 'Demo City Three'
        ]
    ];
     array_unshift(
            $options,
            ['title' => '', 'value' => '', 'label' => __('Please select a city.')]
        );

        return $options;

    }


}
