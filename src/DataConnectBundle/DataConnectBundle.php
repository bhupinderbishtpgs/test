<?php

namespace DataConnectBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use \Pimcore\Model\User\Permission\Definition;

class DataConnectBundle extends AbstractPimcoreBundle {

    /**
     *
     * @var object 
     */
    protected $db;

    public function getNiceName() {
        return "Data Import Mapping";
    }

    public function getInstaller() {
        $path = self::getInstallPath();
        if (!is_dir($path)) {
            $this->install();
            mkdir($path);
        }
        $this->getJsPaths();
        $this->getCssPaths();
        $permissionDefinition = new Definition();
        $permissionDefinition->setKey("plugin_DataConnect_access");
        $permissionDefinition->save();
    }

    public static function getInstallPath() {
        return PIMCORE_PROJECT_ROOT . "/src/DataConnectBundle/install";
    }

    public function install() {

        

        $this->db = \Pimcore\Db::get();
        $table1 = "Drop table IF EXISTS `tblDataConnection`;
                    CREATE TABLE IF NOT EXISTS `tblDataConnection` (
                    `con_name` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
                    `bridge_id` INT(11) NOT NULL,
                    `class_name` VARCHAR(45) NOT NULL,
                    `brickType` VARCHAR(45) NULL DEFAULT NULL COMMENT 'mention type of brick i.e. sql/mysql/csv etc...',
                    `exec_order` TINYINT(4) NULL DEFAULT NULL,
                    `data_key_name` VARCHAR(150) NULL DEFAULT NULL,
                    `overwrite` ENUM('yes', 'no') NULL DEFAULT 'yes',
                    `language_key` VARCHAR(10) NULL DEFAULT NULL,
                    `target_path` VARCHAR(255) NOT NULL,
                    `obj_key_prefix` VARCHAR(50) NULL DEFAULT NULL,
                    `total_records` INT(11) NULL DEFAULT '0',
                    `brick_id` INT NOT NULL,
                    `logs` enum('1','0') DEFAULT NULL,
                    `import_path` VARCHAR(45) NOT NULL,
                    `is_active` enum('1','0') DEFAULT NULL,    
                    `bridge_type` ENUM('internal', 'external') NOT NULL DEFAULT 'internal'
                    COMMENT 'type could be type of internal or external source for data',
                    PRIMARY KEY (`con_name`))
                  ENGINE = InnoDB
                  DEFAULT CHARACTER SET = utf8";
        $this->db->query($table1);

        /////////////
        /////////////

        $table2 = "Drop table IF EXISTS `tblColumnConnectionDetails`;
                    CREATE TABLE IF NOT EXISTS `tblColumnConnectionDetails` (
                    `con_name` VARCHAR(100) NOT NULL,
                    `name` VARCHAR(100) NULL DEFAULT NULL,
                    `label` VARCHAR(150) NULL DEFAULT NULL,
                    `display` ENUM('1', '0') NULL DEFAULT '1' COMMENT '1 : Display column\n0 : Not to be display',
                    `target_col` VARCHAR(150) NULL DEFAULT NULL,
                    `ref_class` VARCHAR(150) NULL DEFAULT NULL,
                    `ref_field` VARCHAR(150) NULL DEFAULT NULL,
                    `col_type` VARCHAR(45) NOT NULL,
                    `selected_attr` VARCHAR(250) NOT NULL,
                    `group_no` VARCHAR(45) NOT NULL)
                      ENGINE = InnoDB
                      DEFAULT CHARACTER SET = utf8";
        $this->db->query($table2);
        /////////////
        /////////////

        $table3 = "Drop table IF EXISTS `tblJobMaster`;
                    CREATE TABLE IF NOT EXISTS `tblJobMaster` (
                    `job_id` INT(11) NOT NULL,
                    `source_records` INT(11) NULL DEFAULT NULL,
                    `user_id` INT(11) NULL DEFAULT NULL,
                    `bridge_id` INT(11) NULL DEFAULT NULL,
                    `con_name` VARCHAR(150) NULL DEFAULT NULL,
                    `start_time` TIMESTAMP NULL DEFAULT NULL,
                    `end_time` TIMESTAMP NULL DEFAULT NULL,
                    `status` ENUM('in-progress', 'done', 'in-complete') NOT NULL DEFAULT 'in-progress',
                    `last_update_time` TIMESTAMP NULL DEFAULT NULL,
                    INDEX `index1` (`job_id` ASC),
                    PRIMARY KEY (`job_id`))
                    ENGINE = InnoDB
                    DEFAULT CHARACTER SET = utf8";
        $this->db->query($table3);
        //////////////
        //////////////

        $table4 = "Drop table IF EXISTS `tblJobQueue`;
                    CREATE TABLE IF NOT EXISTS `tblJobQueue` (
                    `job_id` INT(11) NOT NULL,
                    `source_id` VARCHAR(50) NOT NULL,
                    `dest_id` INT(11) NOT NULL,
                    `status` ENUM('1', '0') NOT NULL DEFAULT '1' COMMENT '\'0\' : fail\n\'1\' : pass',
                    INDEX `index1` (`job_id` ASC))
                      ENGINE = InnoDB
                      DEFAULT CHARACTER SET = utf8";
        $this->db->query($table4);
        /////////////
        //////////////

        $table5 = "Drop table IF EXISTS `tblSrcDestMapping`;
                    CREATE TABLE IF NOT EXISTS `tblSrcDestMapping` (
                      `source_id` varchar(50) NOT NULL,
                      `dest_id` int(11) NOT NULL,
                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $this->db->query($table5);


        $table6 = "Drop table IF EXISTS `tblCSVBrick`; 
                    CREATE TABLE IF NOT EXISTS `tblCSVBrick` (
                    `delimiter` VARCHAR(45) NULL,
                    `file_path` VARCHAR(250) NULL,
                    `id` INT NOT NULL AUTO_INCREMENT,
                    PRIMARY KEY (`id`))
                     ENGINE = InnoDB";
        $this->db->query($table6);

        $table7 = "Drop table IF EXISTS `tblMysqlBrick`;
                    CREATE TABLE IF NOT EXISTS `tblMysqlBrick` (
                    `datasql` TEXT NULL,
                    `dataFrom` VARCHAR(250) NULL,
                    `dataWhere` VARCHAR(45) NULL,
                    `groupBy` VARCHAR(45) NULL,
                    `sqlText` TEXT NULL,
                    `id` INT NOT NULL,
                    PRIMARY KEY (`id`))
                   ENGINE = InnoDB";
        $this->db->query($table7);

        $table8 = "Drop table IF EXISTS `tblSqlBrick`;
                    CREATE TABLE IF NOT EXISTS `tblSqlBrick` (
                    `id` INT NOT NULL,
                    `dataSql` TEXT NULL,
                    `dataFrom` VARCHAR(250) NULL,
                    `dataWhere` VARCHAR(45) NULL,
                    `groupBy` VARCHAR(45) NULL,
                    `sqlText` TEXT NULL,
                    `rownumber` VARCHAR(45) NULL,
                    PRIMARY KEY (`id`))
                  ENGINE = InnoDB";
        $this->db->query($table8);
        /////////////

        return true;
    }

    private static function createFolder($name, $parentid) {

        $folder = \Pimcore\Model\Asset::create($parentid, [
                    'filename' => $name,
                    'type' => 'folder',
                    'userOwner' => 1,
                    'userModification' => 1
        ]);
    }

    public function makeFolder() {

        self::createFolder('DataConnect', 1);
    }

    public function getJsPaths() {

        return array(
            '/bundles/dataconnect/js/plugin.js',
            '/bundles/dataconnect/js/toolbar.js',
            '/bundles/dataconnect/js/dataconnector.js',
            '/bundles/dataconnect/js/connector/db/panel.js',
            '/bundles/dataconnect/js/connector/db/item.js',
            '/bundles/dataconnect/js/definitions/csv.js',
            '/bundles/dataconnect/js/mapping/mappinglist.js',
            '/bundles/dataconnect/js/mapping/mapping.js',
        );
    }

    public function getCssPaths() {
        return array(
            '/bundles/dataconnect/css/connect.css',
        );
    }

    public function getConfig() {

        $path = PIMCORE_PROJECT_ROOT . "/src/DataConnectBundle/plugin.xml";
        $obj = new \Symfony\Component\Serializer\Encoder\XmlEncoder();
        $data = $obj->decode(file_get_contents($path), 'XML');
        return $data['plugin'];
    }

}
