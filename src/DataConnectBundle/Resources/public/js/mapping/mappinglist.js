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
 * This file is used for mapping list panel
 * 
 */

pimcore.registerNS("pimcore.plugin.mappinglist");
pimcore.plugin.mappinglist = Class
		.create({
			initialize : function() {

                            this.getListInfoComplete();
			},

			getListInfoComplete : function(response) {
				this.getTabPanel();
			},
			// mapping list tab panel
			getTabPanel : function() {
				if (!this.panel) {
					this.panel = new Ext.Panel({
						id : "list_mapping",
						title : t('list_mapping_title'),
						border : false,
						layout : "fit",
						closable : true,
						items : [ this.getGrid() ]
					});

					var tabPanel = Ext.getCmp("pimcore_panel_tabs");
					tabPanel.add(this.panel);
					tabPanel.setActiveItem("list_mapping");

					this.panel.on("destroy", function() {
						pimcore.globalmanager.remove("list_mapping");
					}.bind(this));

					pimcore.layout.refresh();
					return this.panel;
				}
			},
			// mapping list grid
			getGrid : function(data) {

				var itemsPerPage = 20;

				Ext.define('mapping', {
					extend : 'Ext.data.Model',
					fields : [ 'id', 'con_name', 'name',
							'sqlText', 'classid','language_key','exec_order', 'status','datetime']
				});

				this.store = Ext
						.create(
								'Ext.data.Store',
								{
									model : 'mapping',
									autoLoad : true,
									proxy : {
										type : 'ajax',
										api : {
											read : '/DataConnect/mapping/load-mapping-attributes'
										},
										reader : {
											type : 'json',
											rootProperty : 'data',
											totalProperty : 'total',
											successProperty : 'success',
										}
									}
								});

				
				
				// paging menu
				this.pagingtoolbar = new Ext.PagingToolbar({
					pageSize : itemsPerPage,
					store : this.store,
					displayInfo : true,
					displayMsg : '{0} - {1} / {2}',
					emptyMsg : t("no_objects_found")
				});

				// add per-page selection
				this.pagingtoolbar.add("-");

				this.pagingtoolbar.add(new Ext.Toolbar.TextItem({
					text : t("items_per_page")
				}));
				this.pagingtoolbar
						.add(new Ext.form.ComboBox(
								{
									store : [ [ 10, "10" ], [ 20, "20" ],
											[ 40, "40" ], [ 60, "60" ],
											[ 80, "80" ], [ 100, "100" ] ],
									mode : "local",
									width : 50,
									value : 20,
									triggerAction : "all",
									listeners : {
										select : function(box, rec, index) {

											this.pagingtoolbar.pageSize = intval(rec.data.field1);
											this.pagingtoolbar.moveFirst();
										}.bind(this)
									}
								}));
				// mapping list grid columns
				var typesColumns = [
						{
							header : t('sno'),
							width : 100,
							sortable : true,
							dataIndex : 'id',
						},
						{
							header : t('connection_name'),
							width : 120,
							sortable : true,
							dataIndex : 'con_name'
						},
						/*{
							header : t('bridge_name'),
							id : "bridge_name",
							width : 100,
							sortable : true,
							dataIndex : 'bridge_id',
						},*/
						{
							header : t('class_name'),
							width : 100,
							sortable : true,
							dataIndex : 'name'
						},
						{
							header : t('class_id'),
							width : 10,
							hidden : true,
							menuDisabled : true,
							sortable : false,
							dataIndex : 'classid'
						},
						{ 
							header : t('language'), 
							width : 100, 
							sortable : true, 
							dataIndex : 'language_key' 
						},
						{ 
							header : t('exec_order'), 
							width : 100, 
							sortable : true, 
							dataIndex : 'exec_order' 
						},
                                                { 
							header : t('status'), 
							width : 120, 
							sortable : true, 
							dataIndex : 'status' 
						},
                                                { 
							header : t('datetime'), 
							width : 150, 
							sortable : true, 
							dataIndex : 'datetime' 
						},


				];
				var that = this;
				this.count = 0;
				
				
				this.grid = new Ext.grid.GridPanel({
					frame : false,
					autoScroll : true,
					store : this.store,
					columnLines : true,
					bbar : this.pagingtoolbar,
					stripeRows : true,
					columns : typesColumns,
					viewConfig : {
						emptyText : t('empty_record'),
						deferEmptyText : false
					},

					
					tbar : [ {
						text : t('refresh'),
						handler : this.onRefresh.bind(this),
						iconCls : "pimcore_icon_reload"
					}
					
					],

				});
				this.grid.on("rowcontextmenu", this.onRowContextmenu);
				return this.grid;

			},
			
			onRowContextmenu: function (grid, record, tr, rowIndex, e, eOpts ) {
				var menu = new Ext.menu.Menu();
		        var data = grid.getStore().getAt(rowIndex);
		        var view = grid.getSelectionModel().getSelection();
		        
		        menu.add(new Ext.menu.Item({
	                text: t('open'),
	                iconCls: "pimcore_icon_open",
	                handler: function () {
	                	if (!Ext.getCmp("data_connector")) {
	    					var dataCon = new pimcore.plugin.data.connector();
	    				} else {
	    					Ext.getCmp("pimcore_panel_tabs").setActiveItem(
	    							"data_connector");
	    				}
	                }
	            }));
		        
		        menu.add(new Ext.menu.Item({
	                text: t('delete'),
	                iconCls: "plugin_data_connect_icon_delete x-action-col-icon",
	                handler : function(data) {
						var mapping = new pimcore.plugin.dataconnectmapping(data.data.con_name, data.data.classid);
						mapping.deleteRecord(view, record);
					}.bind(this, data, view)
	            }));

		        menu.add(new Ext.menu.Item({
	                text: t('execute'),
	                iconCls: "plugin_data_connect_icon_execute",
	                handler : function(data) {
						var mapping = new pimcore.plugin.dataconnectmapping(data.data.con_name, data.data.classid);
						mapping.runRecord(view, record);
					}.bind(this, data, view)
	            }));
		        
		        e.stopEvent();
		        menu.showAt(e.pageX, e.pageY);
			},
			
			// refersh listing grid
			onRefresh : function(btn, ev) {
				this.store.reload();
				this.grid.getView().refresh();
			},

		});