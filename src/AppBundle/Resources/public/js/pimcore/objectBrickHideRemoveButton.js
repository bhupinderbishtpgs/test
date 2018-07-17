/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

pimcore.registerNS("pimcore.plugin.objectBrickHideRemoveButton");

pimcore.plugin.objectBrickHideRemoveButton = Class.create(pimcore.plugin.admin, {

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        //alert("AppBundle ready!");
    },

    /**
     * @param obj
     * @param type
     */
    postOpenObject: function (obj, o_type) {
        /**
         * This code is responsible for hideing remove object bricks button for auto-mapped object bricks (Product, BoxandKit & Component)
         */
        Ext.override(pimcore.object.tags.objectbricks, {
            getDeleteControl: function (type, blockElement) {
                var items = [];
                if (!this.preventDelete[type]) {
                    items.push({
                        cls: "pimcore_block_button_minus",
                        iconCls: "pimcore_icon_minus",
                        listeners: {
                            "click": this.removeBlock.bind(this, blockElement)
                        }
                    });
                }
                items.push({
                    xtype: "tbtext",
                    text: ts(type)
                });
                if (o_type !== 'object' || obj.data.general.o_className === 'Product') {
                    if (this.fieldConfig.name === "productCommonAttributes" || this.fieldConfig.name === "additionalAttributes") {
                        var toolbar = new Ext.Toolbar({});
                        return toolbar;
                    }
                } else if (o_type !== 'object' || obj.data.general.o_className === 'BoxandKit' || obj.data.general.o_className === 'Component') {
                    var toolbar = new Ext.Toolbar({});
                    return toolbar;
                }
                var toolbar = new Ext.Toolbar({
                    items: items
                });
                return toolbar;
            }
        });
    },

});

var objectBrickHideRemoveButton = new pimcore.plugin.objectBrickHideRemoveButton();