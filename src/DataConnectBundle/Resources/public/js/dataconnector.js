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
 * This file is used for initialize data connector panel
 * 
 */

pimcore.registerNS("pimcore.plugin.data.connector");
pimcore.plugin.data.connector = Class.create({

	initialize : function(parent) {

		this.getPanel();
	},

	activate : function() {

		var tabPanel = Ext.getCmp("pimcore_panel_tabs");
	},
	// Data Connector menu item panel
	getPanel : function() {

		var editor = new pimcore.plugin.db.panel();

		if (!this.panel) {
			this.panel = new Ext.Panel({
				id : "data_connector",
				title : 'Data Import Mapping',
				iconCls : "data_connector_icon",
				bodyStyle : "padding: 10px;",
				layout : "fit",
				closable : true,
				items : [ editor.getTabPanel() ]
			});

			var tabPanel = Ext.getCmp("pimcore_panel_tabs");
			tabPanel.add(this.panel);
			tabPanel.setActiveItem("data_connector");

			this.panel.on("destroy", function() {
				pimcore.globalmanager.remove("data_connector");
			}.bind(this));

			pimcore.layout.refresh();
		}

		return this.panel;
	}

});