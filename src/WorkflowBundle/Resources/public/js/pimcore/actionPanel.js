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

pimcore.registerNS("pimcore.plugin.actionPanel");

pimcore.plugin.actionPanel = Class.create(pimcore.object.abstract, {
    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        Ext.override(pimcore.workflowmanagement.actionPanel, {
            //This function is overrided inorder to add message in workflow action box
            getWorkflowFormPanel: function ()
            {
                if (!this.workflowFormPanel) {
                    //initialise the formpanel as it doesn't exist
                    this.workflowFormPanel = new Ext.form.FormPanel({
                        border: false,
                        title: 'Save your changes first if any. Otherwise data may loose.',
                        frame: false,
                        bodyStyle: 'padding:10px',
                        items: this.getInitialFormPanelItems(),
                        defaults: {
                            labelWidth: 200
                        },
                        cls: 'formPanelTitle',
                        collapsible: false,
                        autoScroll: true
                    });
                }
                return this.workflowFormPanel
            },

        });
    }
});

var actionPanel = new pimcore.plugin.actionPanel();