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
 * This file is used for mapping configuration panel
 * 
 */
 
pimcore.registerNS("pimcore.plugin.dataconnectmapping");
pimcore.plugin.dataconnectmapping = Class.create({
	initialize: function (conName, classId) {
		
        this.conName = conName;
        this.classId = classId;
        
    },
    // Get mapping configuration    
    getFileInfo: function () {
        Ext.Ajax.request({
            url: "/plugin/DataConnect/mapping/get-mapping-info",
            params: {
                name: this.conName,
                classId: this.classId,
                method: "post",
            },
            success: this.getFileInfoComplete.bind(this)
        });
    },
    
    getFileInfoComplete: function (response) {
    	
        var data = Ext.decode(response.responseText);
        
        if (data.success) {
            this.showDataWindow(data);
        }
        else {
        	pimcore.helpers.showNotification(t('error'),t('error_info_msg'),'error');
        }
    },
    // Show mapping screen    
    showDataWindow: function (data) {
        this.importJobTotal = data.rows;


        var dataStore = new Ext.data.JsonStore({
            autoDestroy: true,
            data: data,
            proxy: {
                type: 'memory',
                reader: {
                    type: 'json',
                    rootProperty: 'dataPreview'
                }
            },
            fields: data.dataFields
        });
        // Mapping configuration data
        var mappingStore = new Ext.data.Store({
            autoDestroy: true,
            data: data,
            proxy: {
                type: 'memory',
                reader: {
                    type: 'json',
                    rootProperty: 'mappingStore'
                }
            },
            fields: ["source", "firstRow", "target", "config"]
        });

        var targetFields = data.targetFields;
        targetFields.push(["",t("ignore")]);

        targetFields = new Ext.data.ArrayStore({
            data: targetFields,
            fields: ['value','text']
        });

        var sourceFields = [];
        for (i = 0; i < data.cols; i++) {
            sourceFields.push([data.mappingStore[i].firstRow,data.mappingStore[i].firstRow]);
        }

        this.cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToEdit: 1
        });

        // Mapping configuration grid view
        this.mappingGrid = new Ext.grid.Panel({
            store: mappingStore,
            plugins: [this.cellEditing],
            columns: [
                {
                    header: t('source'),sortable: false,
                    dataIndex: "source",
                    renderer: function(value, p, r) {
                        return r.data.source + " (" + r.data.firstRow + ")";
                    }.bind(this),
                    flex: 1
                },
                {
                	header: t('target'), 
                	sortable: false, 
                	dataIndex: "target", 
                	flex: 1, 
                	editor: new Ext.form.ComboBox({
	                    store: targetFields,
	                    mode: "local",
	                    triggerAction: "all",
	                    valueField: 'value',
	                    value : 'Category',
	                    displayField: 'text'
	                   /* ,listeners : {
	                    	change: this.changeStore(combo, value)  
	                        change :  function (combo, newValue, oldValue, eOpts) {
	                            if (newValue === '') {
	                                return;
	                            }
	                            var dataTypeArr = ["objectbrick"];
	                            var dataType = combo.lastMutatedValue.match(/\((.*)\)/)[1];
	                            var gridRecord = combo.up('grid').getSelectionModel().getSelection();
	                            var dialog = new pimcore.plugin.dialog.configDialog();
	                            if($.inArray(dataType, dataTypeArr) !== -1) {
	                            	//dialog.testConfig(dataType, this.classId);
	                            } else {
	                            	//console.log("working...");
	                            }
	
	                        }
	                    }*/
                	})
                },
                {
                    header: t('config'),
                    sortable: false,
                    dataIndex: "config",
                    editor: 'textfield',
                    flex: 1
                }
            ],
            forceFit: true
        });
        
 

        var filenameMappingStore = sourceFields;
        

        // Settings form layout
        this.settingsForm = new Ext.form.FormPanel({
            items: [
                {
                    xtype: "combo",
                    name: "objkeyname",
                    store: filenameMappingStore,
                    mode: "local",
                    triggerAction: "all",
                    fieldLabel: 'Obj Key Name<span class="req" style="color:red">*</span>',
                    width: 300,
                    value: data.objkeyname,
                    tip: t('obj_key_name_tooltip'),
                    listeners : {
                        render: function (el) {
                            Ext.create('Ext.tip.ToolTip', {
                                target: el.getEl(),
                                html: el.tip
                            });
                        },
                    }
                },
                {
                    xtype: "combo",
                    name: "keyname",
                    store: filenameMappingStore,
                    mode: "local",
                    triggerAction: "all",
                    fieldLabel: 'Key Name<span class="req" style="color:red">*</span>',
                    width: 300,
                    value: data.keyname,
                    tip: t('data_key_name_tooltip'),
                    listeners : {
                        render: function (el) {
                            Ext.create('Ext.tip.ToolTip', {
                                target: el.getEl(),
                                html: el.tip
                            });
                        },
                    }
                },
                {
                    xtype      : 'fieldcontainer',
                    fieldLabel : t('overwrite_label'),
                    defaultType: 'checkboxfield',
                    width: 500,
                    tip: t('overwrite_checkbox'),
                    listeners : {
                        render: function (el) {
                            Ext.create('Ext.tip.ToolTip', {
                                target: el.getEl(),
                                html: el.tip
                            });
                        },
                    },
                    items: [
                        {
                            name      : 'overwrite',
                            inputValue: 1,
                            checked   : (data.overWrite=='yes') ? true : false,
                            id        : 'overwrite',
                        }
                    ]
                }
            ],
            bodyStyle: "padding: 10px;"
        });
        // Mapping configuration pop up window
        this.dataWin = new Ext.Window({
            modal: true,
            width: 700,
            height: 500,
            layout: "fit",
            items: [
                {
                    xtype: "tabpanel",
                    activeTab: 0,
                    items: [
                        {
                            xtype: "panel",
                            title: t("data_mapping"),
                            layout: "fit",
                            items: [this.mappingGrid]
                        },
                        {
                            xtype: "panel",
                            title: t("settings"),
                            layout: "fit",
                            items: [this.settingsForm],
                            buttons: [
                                {
                                    text: t('save'),
                                    handler: this.saveMapping
									.bind(this, 'Save')
                                }
                            ]
                        }
                    ]
                }
            ],
            title: t('save')
        });

        this.dataWin.show();
    
    },
    
    changeStore: function(combo, value) {
    	//console.log('welcome combo');
    	//console.log(combo);
    },
    
    // Save mapping configuration    
    saveMapping : function(action) {
		//This will save file import mapping information
		
		var formData = this.settingsForm.getForm().getFieldValues();
		
		// get mapping
		var mappedData = this.mappingGrid.getStore().queryBy(
				function(record, id) {
					return true;
				});
		if(formData.keyname != 'undefined') {
			var keyName = formData.keyname;
		} else {
			var keyName = '';
		}
                if(formData.objkeyname != 'undefined') {
			var objkeyname = formData.objkeyname;
		} else {
			var objkeyname = '';
		}
		var keyFlag = true;
		var mappingData = [];
		var tmData = [];
		for (var i = 0; i < mappedData.items.length; i++) {
			tmData = [];

			var keys = Object.keys(mappedData.items[i].data);
			
			for (var u = 1; u < keys.length; u++) {
				tmData.push(mappedData.items[i].data[keys[u]]);
			
			}
			
			if(tmData[0]==keyName && (tmData[1]==null || tmData[1]=='')) {
				keyFlag = false;
			}
			mappingData.push(Ext.encode(tmData));
		}
		
		if(this.settingsForm.getForm().isValid() && keyName!=null && keyFlag && objkeyname!=null) {
		Ext.Ajax
				.request({
					url : "/plugin/DataConnect/mapping/save-mapping-data",
					params : {
						classId : this.classId,
						conName : this.conName,
						mappingData : Ext.encode(mappingData),
						keyName : keyName,
						overWrite : formData.overwrite,
                        objKeyName : objkeyname,
					},
					method : 'get',
					success : function(response, xhr) {
						var res = Ext.decode(response.responseText);
		            	if (res.success) {
		                    pimcore.helpers.showNotification(t('success'),t('mapping_save_msg'), 'success');

		                } else {
		                    pimcore.helpers.showNotification(t('error'),t('error_saving_msg'),'error');
		                }
					}
				});
				this.dataWin.close();
		} else {
			if(keyFlag==false) {
				Ext.Msg.alert(t('error'),t('invalid_mapping_msg'));
			}
			else {
				Ext.Msg.alert(t('error'),t('keyname_alert_msg'));
			}
				
		}

		
		
	},
	// View mapping configuration
	viewRecord : function(record) {
		
		
		Ext.Ajax.request({
            url: "/plugin/DataConnect/mapping/get-mapping-info",
            params: {
                name: this.conName,
                classId: this.classId,
                method: "post",
            },
            success: function (response) {
            	
            	var data = Ext.decode(response.responseText);
            	
            	if (data.success) {
            		// Mapping configuration data
                    var mappingViewStore = new Ext.data.Store({
                        autoDestroy: true,
                        data: data,
                        proxy: {
                            type: 'memory',
                            reader: {
                                type: 'json',
                                rootProperty: 'mappingStore'
                            }
                        },
                        fields: ["source", "firstRow", "target","config"]
                    });
                    
                    // Mapping configuration grid view
                    this.mappingViewGrid = new Ext.grid.Panel({
                        store: mappingViewStore,
                        plugins: [/*this.cellEditing*/],
                        columns: [
                            {
                                header: t('source'),sortable: false,
                                dataIndex: "source",
                                renderer: function(value, p, r) {
                                    return r.data.source + " (" + r.data.firstRow + ")";
                                }.bind(this),
                                flex: 1
                            },
                            {
                            	header: t('target'), sortable: false, 
                            	dataIndex: "target", 
                            	flex: 1
                            },
                            {
                                header: t('config'),
                                sortable: false,
                                dataIndex: "config",
                                flex: 1
                            }
                        ],
                        forceFit: true
                    });
                    
                    var sourceFields = [];
                    for (i = 0; i < data.cols; i++) {
                        sourceFields.push([data.mappingStore[i].firstRow,data.mappingStore[i].firstRow]);
                    }
                    
                    // Settings form layout
                    this.settingsForm = new Ext.form.FormPanel({
                        items: [
    							{
    							    xtype: "textfield",
    							    name: "keyname",
    							    value: data.keyname,
    							    fieldLabel: t('key_name_field'),
    							    disabled: true
    							},
                                {
    							    xtype: "textfield",
    							    name: "objkeyname",
    							    value: data.objkeyname,
    							    fieldLabel: t('obj_key_name_field'),
    							    disabled: true
    							},
    							{
    			                    xtype      : 'fieldcontainer',
    			                    fieldLabel : t('overwrite_label'),
    			                    defaultType: 'checkboxfield',
    			                    width: 500,
    			                    items: [
    			                        {
    			                            name      : 'overwrite',
    			                            inputValue: 1,
    			                            checked   : (data.overWrite=='yes') ? true : false,
    			                            id        : 'overwrite',
    			                            disabled: true
    			                        }
    			                    ]
    			                },
    			                {
    							    xtype: "textfield",
    							    name: "Description",
    							    //value: data.keyname,
    							    fieldLabel: t('key_name_field')
//    							    disabled: true
    							}
                        ],
                        bodyStyle: "padding: 10px;"
                    });
                    
                    this.dataViewWin = new Ext.Window({
                        modal: true,
                        width: 700,
                        height: 500,
                        layout: "fit",
                        items: [this.mappingViewGrid],
                        items: [
                                {
                                    xtype: "tabpanel",
                                    activeTab: 0,
                                    items: [
                                        {
                                            xtype: "panel",
                                            title: t('data_mapping_title'),
                                            layout: "fit",
                                            items: [this.mappingViewGrid]
                                        },
                                        {
                                            xtype: "panel",
                                            title: t('setting_title'),
                                            layout: "fit",
                                            items: [this.settingsForm],
                                        }
                                    ]
                                }
                        ],
                        title: t('view'),
                    });

                    this.dataViewWin.show();
            	
            	} else {
            		
            		pimcore.helpers.showNotification(t('error'),t('error_info_msg'),'error');
            	}
            	
            }
        });
		
		
		
		
	},
	// edit mapping record
	editRecord : function(record) {
		this.getFileInfo();
	},
	// delete mapping record
	deleteRecord : function(view,record) {
		
		Ext.Msg.confirm(t('confirmation'), t('delete_confirmation'), function(confirm){

			if(confirm === "yes") {
	        	
	            Ext.Ajax.request({
	                url: "/DataConnect/mapping/delete-mapping",
	                params: {
	                    name: this.conName,
	                    classId: this.classId,
	                    method: "post",
	                },
	                success: function(response) {
	                	var data = Ext.decode(response.responseText);
	                	
	                	if (data.success) {
	                		//view.getStore().remove(record);
	                		pimcore.helpers.showNotification(t('success'),t('mapping_deleted_msg'),'success');
	                		
	                	} else {
	                		
	                		Ext.MessageBox.alert(t("error"),t('deletion_error'));
	                	}
	                }
	            });
	        }
	        else if(confirm === "no") {

	        }
	    }, this);
		

	},
    execute : function(conn,status) {
        
        Ext.Ajax.request({
            url: "/DataConnect/connection/run",
            params: {
                status : status,
                name: conn,
                method: "post",
            },
            success: function(response) {
                    var data = Ext.decode(response.responseText);

                    if (data.success) {

                            pimcore.helpers.showNotification(t('success'),data.msg,'success');

                    } else {

                            Ext.MessageBox.alert(t('error'),data.msg);
                    }
            }
        });
        
    },
    
	runRecord : function(view,record) {
		var mapping = new pimcore.plugin.dataconnectmapping(record.get('con_name'),record.get('classid'));	
		Ext.Msg.confirm(t('confirmation'), t('execute_msg'), function(confirm){
			if(confirm === "yes") {
	            Ext.Ajax.request({
	                url: "/DataConnect/connection/checkauth",
	                params: {
	                    name: this.conName,
	                    classId: this.classId,
	                    method: "post",
	                },
	                success: function(res) {
                        var data = Ext.decode(res.responseText);
                        if (data.success) {
                            //Execution
                            mapping.execute(data.conn,2);
                        } else {
                            if(data.checkresume != undefined) {
                                Ext.Msg.show({
                                    title : t('confirmation'),
                                    msg : t('unfinished_job_msg'),
                                    width : 400,
                                    height : 200,
                                    closable : false,
                                    buttonText : 
                                    {
                                        yes : t('resume'),
                                        no : t('start_beginning'),
                                        cancel : t('wait')
                                    },
                                    multiline : false,
                                    fn : function(buttonValue, inputText, showConfig){
                                        
                                        if(buttonValue=='yes') {
                                            mapping.execute(data.conn,1);
                                        } else if(buttonValue=='no') {
                                            mapping.execute(data.conn,2);
                                        }
                                        
                                    },
                                    icon : Ext.Msg.QUESTION
                                });
                            } else {
                                Ext.MessageBox.alert(t('error'),data.msg);
                            }
                        }
                    }
                });
	        }
		}, this);
	},
});