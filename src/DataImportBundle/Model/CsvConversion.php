<?php

namespace DataImportBundle\Model;

class CsvConversion {

    /**
     * @var \Pimcore\Model\Asset
     */
    private $asset = null;

    /**
     * @var String 
     */
    private $fileName = null;

    /**
     * @var String 
     */
    private $filePath = null;

    /**
     * @var String 
     */
    public static $conversionPath = PIMCORE_PROJECT_ROOT . '/src/DataImportBundle/Resources/converted-csv';

    /**
     * Constant for allowed excel mime types
     */
    CONST EXCEL_ALLOWED_MIMETYPE = [
        "application/vnd.ms-excel",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
    ];

    /**
     * Constructor
     * 
     * @param \Pimcore\Model\Asset $asset
     * @throws \Exception
     */
    public function __construct($asset, $csvPath = null) {
        $this->asset = $asset;
        if (!in_array($this->asset->getMimeType(), self::EXCEL_ALLOWED_MIMETYPE)) {
            throw new \Exception("Invalid mime type");
        }
        $this->convert($csvPath);
    }

    /**
     * Convert Excel to CSV
     */
    protected function convert($csvPath) {
        $extension = pathinfo($this->asset->getFilename(), PATHINFO_EXTENSION);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($extension));
        $fullPath = PIMCORE_ASSET_DIRECTORY . $this->asset->getPath() . $this->asset->getFilename();
        $spreadsheet = $reader->load($fullPath);

        $this->fileName = $this->asset->getId() . '.csv';
        if ($csvPath) {
            $this->filePath = $csvPath . '/' . $this->fileName;
        } else {
            $this->filePath = self::$conversionPath . '/' . $this->fileName;
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Csv");
        $writer->save($this->filePath);
    }

    /**
     * getFilePath
     * 
     * @return string
     */
    public function getFilePath() {
        return $this->filePath;
    }

    /**
     * getFileName
     * 
     * @return string
     */
    public function getFileName() {
        return $this->fileName;
    }

}
