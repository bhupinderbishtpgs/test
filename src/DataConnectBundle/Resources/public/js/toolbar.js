/**
 * Pimcore
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
 * This file is used for adding toolbar menu configration
 *
 */

pimcore.registerNS("pimcore.plugin.toolbar");
pimcore.plugin.toolbar = Class
    .create({

        initialize: function () {
            //
        },

        // This function add "Data Connect" menu icon in the left main menu
        leftNavigation: function () {
            // add a sub-menu item under "Extras" in the main menu
            var toolbar = pimcore.globalmanager.get("layout_toolbar");

            var perspectiveCfg = pimcore.globalmanager.get("perspective");

            if (true || perspectiveCfg.inToolbar("Connect")) {

                // init
                var menuItems = toolbar.connectorMenu;
                
                if (!menuItems) {
                    menuItems = new Ext.menu.Menu({
                        cls: "pimcore_navigation_flyout"
                    });
                    toolbar.connectorMenu = menuItems;
                }
                var user = pimcore.globalmanager.get("user");

                var insertPoint = Ext.get("pimcore_menu_settings");
                if (!insertPoint) {
                    var dom = Ext.dom.Query
                        .select('#pimcore_navigation ul li:last');
                    insertPoint = Ext.get(dom[0]);
                }

                var item = {
                    text: 'Data Import Mapping',
                    iconCls: "plugin_dataconnector",
                    handler: this.showDataConnector
                };

                // add to menu
                menuItems.add(item);
                
//                item = {
//                    text: t('mapping_list_menu'),
//                    iconCls: "plugin_data_mapping_list",
//                    handler: this.showDataMapping
//                };
//                menuItems.add(item);

                // add connector main menu
              if (menuItems.items.length > 0) {

                    this.navEl = Ext.get(insertPoint.insertHtml("afterEnd",
                        '<li data-menu-tooltip="Data Import Mapping" title="Data Import Mapping" id="pimcore_menu_dataconnector" class="pimcore_menu_item compatibility">'
                        + 'Connector' + '</li>'));

                    this.navEl.on("mousedown", toolbar.showSubMenu
                        .bind(menuItems));
                }
            }
        },

        // show data connector panel
        showDataConnector: function () {
            if (!Ext.getCmp("data_connector")) {
                var dataCon = new pimcore.plugin.data.connector();
            } else {
                Ext.getCmp("pimcore_panel_tabs").setActiveItem(
                    "data_connector");
            }
        },

        //data mapping call
        showDataMapping: function () {
            if (!Ext.getCmp("list_mapping")) {
                var mappinglist = new pimcore.plugin.mappinglist();
            } else {
                Ext.getCmp("pimcore_panel_tabs").setActiveItem(
                    "list_mapping");
            }
        }

    });
var toolbar = new pimcore.plugin.toolbar();