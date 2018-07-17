<?php

/**
 * DataConnectionService
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 *
 * @desc This file is used for dataconnection service manipulation
 *
 */

namespace DataImportBundle\Service\DataRetriever;

/**
 * DataRetrieverService Class
 */
class CSVDataRetriever {

    /**
     * Get data of csv
     *
     * @param string $connIds            
     * @return array
     * @throws \Exception
     */
    public function getCSVData($filePath,$excelFlag = false) {
        try {
            $firstRowData = array();
            if (empty($filePath)) {
                return $firstRowData;
            }
            
            if($excelFlag == true) {
                $csvFilePath = $filePath;
            } else {
                $csvFilePath = PIMCORE_ASSET_DIRECTORY . $filePath;
            }
            


            $dialect = \Pimcore\Tool\Admin::determineCsvDialect($csvFilePath);
            $data = [];
            $count = 0;
            if (($handle = fopen($csvFilePath, 'r')) !== false) {
                while (($rowData = fgetcsv($handle, 0, $dialect->delimiter, $dialect->quotechar, $dialect->escapechar)) !== false) {
                    if ($count == 0) {
                        foreach ($rowData as $key => $value) {
                            unset($rowData[$key]);
                            $rowData[utf8_decode(trim($key))] = utf8_decode(trim($value));
                        }
                        $firstRowData = $rowData;
                    } else {
                        $tmpData = [];
                        foreach ($firstRowData as $key => $value) {
//                            $tmpData[$value] = utf8_encode($rowData[$key]);
                            $tmpData[$value] = $rowData[$key];
                        }
                        $data[] = $tmpData;
                        $cols = count($rowData);
                    }
                    $count++;
                }
                fclose($handle);
            }
            return $data;
        } catch (\Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
    }

}
