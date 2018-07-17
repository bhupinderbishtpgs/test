/**
 * Pimcore
 *
 * This source file is available under two different licenses: - GNU General
 * Public License version 3 (GPLv3) - Pimcore Enterprise License (PEL) Full
 * copyright and license information is available in LICENSE.md which is
 * distributed with this source code.
 *
 * @copyright Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license http://www.pimcore.org/license GPLv3 and PEL
 */
/**
 *  This file is used for data conector view panel
 */

pimcore.registerNS("pimcore.plugin.db.item");
pimcore.plugin.db.item = Class
    .create({
        initialize: function (data, connectionId, parentPanel) {
            this.parentPanel = parentPanel;
            this.connectionId = connectionId;
            this.data = data;
            this.currentElements = [];
            this.currentElementCount = 0;
            this.addLayout();
            this.brickVal = "";
            this.collectionVal = "";
        },

        // add item layout
        addLayout: function () {
            var panelButtons = [];
            panelButtons.push({
                text: 'Save',
                id: 'save_next' + this.data.name,
                iconCls: "pimcore_icon_apply",
                handler: this.save.bind(this, 'saveNext')
            });

            var mappingPanelButtons = [];
            mappingPanelButtons.push({
                text: 'Save',
                id: 'column_next' + this.data.name,
                iconCls: "pimcore_icon_apply",
                handler: this.getMapping.bind(this)
            });
            mappingPanelButtons.push({
                text: 'Reload',
                id: 'column_reset' + this.data.name,
                iconCls: "pimcore_icon_reload",
                handler: this.resetMappingPanel.bind(this)
            });

            var brickmappingPanelButtons = [];
            brickmappingPanelButtons.push({
                text: 'Save',
                iconCls: "pimcore_icon_apply",
                id: "brick_next" + this.data.name,
                handler: this.getAdvancedMappingData.bind(this)
            });
            brickmappingPanelButtons.push({
                text: 'Reload',
                iconCls: "pimcore_icon_reload",
                id: "brick_reset" + this.data.name,
                handler: this.resetBrickPanel.bind(this)
            });

            var emptyBrickPanelButtons = [];
            emptyBrickPanelButtons.push({
                text: t('next'),
                id: 'empty_brick_next' + this.data.name,
                iconCls: "pimcore_icon_apply",
                handler: this.callNextPanel.bind(this, 'ObjectBrick')
            });

            var fieldcollectionmappingPanelButtons = [];
            fieldcollectionmappingPanelButtons.push({
                text: 'Save',
                iconCls: "pimcore_icon_apply",
                id: "collection_next" + this.data.name,
                handler: this.getfieldCollectionMappedData.bind(this)
            });
            fieldcollectionmappingPanelButtons.push({
                text: 'Reload',
                iconCls: "pimcore_icon_reload",
                id: "collection_reset" + this.data.name,
                handler: this.resetCollectionPanel.bind(this)
            });

            var emptyFieldCollectionPanelButtons = [];
            emptyFieldCollectionPanelButtons.push({
                text: t('next'),
                id: 'empty_fieldcollection_next' + this.data.name,
                iconCls: "pimcore_icon_apply",
                handler: this.callNextPanel.bind(this, 'FieldCollection')
            });

            this.columnStore = Ext.create('Ext.data.Store', {
                autoDestroy: false,
                proxy: {
                    type: 'memory'
                },
                data: [],
                fields: ["name", "display", "label"]
            });

            var checkDisplay = new Ext.grid.column.Check({
                header: t('col_display'),
                dataIndex: "display",
                width: 70
            });

            this.cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
                clicksToEdit: 1
            });

            this.columnGrid = Ext.create('Ext.grid.Panel', {
                store: this.columnStore,
                plugins: [this.cellEditing],
                columns: [{
                    header: t('col_name'),
                    sortable: false,
                    dataIndex: 'name',
                    editable: false,
                    width: 180
                }, checkDisplay, {
                    header: t('col_label'),
                    sortable: false,
                    dataIndex: 'label',
                    editable: true,
                    width: 150,
                    editor: new Ext.form.TextField({})
                }],
                columnLines: true,
                trackMouseOver: true,
                stripeRows: true,
                autoHeight: true,
                title: t('column_configuration_title')
            });

            this.panel = new Ext.Panel({
                region: "center",
                id: "pimcore_sql_panel_" + this.data.name,
                title: this.data.name,
                bodyStyle: "padding: 10px;",
                closable: true,
                labelWidth: 150,
                name: "data_connect_panel",
                layout: {
                    type: 'accordion',
                    titleCollapse: true,
                    animate: true
                },
                items: [
                    {
                        title: 'General Definition',
                        scrollable: true,
                        id: "panel1" + this.data.name,
                        items: [
                            this.getGeneralDefinitionPanel(),
                            this.getSourceDefinitionPanel(),
                            this.columnGrid
                        ],
                        buttons: panelButtons,
                        tip: t('panel1_tooltip'),
                        iconCls: "plugin_data_connect_question_icon panel-tabs",
                        listeners: {
                            afterrender: this.getColumnSettings.bind(this)
                        }
                    }
                ]
            });

            this.mappingPanel = new Ext.form.FormPanel({
                addRow: function (mappingGrid) {
                    this.add({
                        name: "mapping_grid",
                        title: 'Mapping Grid',
                        items: [mappingGrid],
                        scrollable: true,
                        top: "10px"
                    });
                }

            });

            this.brickMappingPanel = new Ext.form.FormPanel({
                addRow: function (brickMappingGrid) {
                    this.add({
                        name: "brick_mapping_grid",
                        title: 'Brick Mapping Grid',
                        items: [brickMappingGrid],
                        scrollable: true,
                        top: "10px"
                    });
                },
                name: "brick_mapping_panel"
            });

            this.fieldCollectionMappingPanel = new Ext.form.FormPanel({
                addRow: function (fieldCollectionMappingGrid) {
                    this.add({
                        name: "fieldcollection_mapping_grid",
                        title: 'Field Collection Mapping Grid',
                        items: [fieldCollectionMappingGrid],
                        scrollable: true,
                        top: "10px"
                    });
                },
                name: "fieldcollection_mapping_panel"
            });

            this.settingMappingPanel = new Ext.form.FormPanel({
                addRow: function (setting) {
                    this.add({
                        name: "setting_mapping_panel",
                        title: 'Setting Panel',
                        items: [setting],
                        scrollable: true,
                        top: "10px"
                    });
                },
                name: "fieldcollection_mapping_panel"
            });

            this.panel.add({
                name: "column_mapping_panel",
                title: "Column Mapping",
                items: [this.mappingPanel],
                scrollable: true,
                id: "panel2" + this.data.name,
                buttons: mappingPanelButtons,
                tip: t('panel2_tooltip'),
                iconCls: "plugin_data_connect_question_icon panel-tabs"
            });

            if (this.data.brick.length > 0 || this.data.columnConfiguration.length == 0) {
                this.panel.add({
                    name: "brick_mapping_panel",
                    title: "Specification Column Mapping",
                    items: [this.brickMappingPanel],
                    scrollable: true,
                    id: "panel3" + this.data.name,
                    buttons: brickmappingPanelButtons,
                    tip: t('panel3_tooltip'),
                    iconCls: "plugin_data_connect_question_icon panel-tabs"
                });
            } else {
                this.panel.add({
                    name: "brick_mapping_panel",
                    title: "Specification Column Mapping",
                    items: [this.brickMappingPanel],
                    scrollable: true,
                    id: "panel3" + this.data.name,
                    buttons: emptyBrickPanelButtons,
                    tip: t('panel3_tooltip'),
                    iconCls: "plugin_data_connect_question_icon panel-tabs"
                });
            }

            if (this.data.collection.length > 0 || this.data.columnConfiguration.length == 0) {
                this.panel.add({
                    name: "fieldcollection_mapping_panel",
                    title: "Collection Mapping",
                    items: [this.fieldCollectionMappingPanel],
                    scrollable: true,
                    id: "panel4" + this.data.name,
                    buttons: fieldcollectionmappingPanelButtons,
                    tip: t('panel4_tooltip'),
                    iconCls: "plugin_data_connect_question_icon panel-tabs"
                });
            } else {
                this.panel.add({
                    name: "fieldcollection_mapping_panel",
                    title: "Field Collection Mapping",
                    items: [this.fieldCollectionMappingPanel],
                    scrollable: true,
                    id: "panel4" + this.data.name,
                    buttons: emptyFieldCollectionPanelButtons,
                    tip: t('panel4_tooltip'),
                    iconCls: "plugin_data_connect_question_icon panel-tabs"
                });
            }

            this.panel.add({
                name: "settings_panel",
                title: "Key Column Mapping",
                items: [this.settingMappingPanel],
                scrollable: true,
                id: "panel5" + this.data.name,
                tip: t('panel5_tooltip'),
                iconCls: "plugin_data_connect_question_icon panel-tabs"
            });

            this.parentPanel.getEditPanel().add(this.panel);
            this.parentPanel.getEditPanel().setActiveTab(this.panel);

            pimcore.layout.refresh();

            var scope = this;
            $("#panel1" + this.data.name).find('.panel-tabs').on('mouseover', function (k, v) {
                $(this).attr('title', t('panel1_tooltip'));
            });
            $("#panel2" + this.data.name).find('.panel-tabs').on('mouseover', function (k, v) {
                $(this).attr('title', t('panel2_tooltip'));
            });
            $("#panel3" + this.data.name).find('.panel-tabs').on('mouseover', function (k, v) {
                $(this).attr('title', t('panel3_tooltip'));
            });
            $("#panel4" + this.data.name).find('.panel-tabs').on('mouseover', function (k, v) {
                $(this).attr('title', t('panel4_tooltip'));
            });

            $("#panel5" + this.data.name).find('.panel-tabs').on('mouseover', function (k, v) {
                $(this).attr('title', t('panel5_tooltip'));
            });

            Ext.Ajax.request({
                url: "/DataConnect/connection/check-mapping",
                method: "post",
                params: {
                    name: this.data.name
                },
                success: function (response) {
                    var res = Ext.decode(response.responseText);
                    if (res.success) {
                        $("#panel2" + this.data.name + "_header").on('click', function (k, v) {
                            scope.getFileInfo();
                        });

                        $("#panel3" + this.data.name + "_header").on('click', function (k, v) {
                            scope.getBrickMappingAttributes();
                        });
                        $("#panel4" + this.data.name + "_header").on('click', function (k, v) {
                            scope.getFieldCollectionAttributes();
                        });

                        $("#panel5" + this.data.name + "_header").on('click', function (k, v) {
                            scope.getDataForSettingForm();
                        });

                    }
                }.bind(this)
            });
        },

        resetSettingPanel: function () {
            this.settingMappingPanel.remove(this.settingsFormPanel);
            this.getDataForSettingForm();
        },

        resetBrickPanel: function () {
            var brickPanel = Ext.getCmp("panel3" + this.data.name);
            brickPanel.remove(this.brickMappingPanel);

            this.brickMappingPanel = new Ext.form.FormPanel({
                addRow: function (brickMappingGrid) {
                    this.add({
                        name: "brick_mapping_grid",
                        title: 'Brick Mapping Grid',
                        items: [brickMappingGrid],
                        scrollable: true,
                        top: "10px"
                    });
                },
                name: "brick_mapping_panel"
            });
            brickPanel.add(this.brickMappingPanel);
            this.getBrickMappingAttributes();
        },

        resetCollectionPanel: function () {
            var collectionPanel = Ext.getCmp("panel4" + this.data.name);
            collectionPanel.remove(this.fieldCollectionMappingPanel);
            this.fieldCollectionMappingPanel = new Ext.form.FormPanel({
                addRow: function (fieldCollectionMappingGrid) {
                    this.add({
                        name: "collection_mapping_grid",
                        title: 'Collection Mapping Grid',
                        items: [fieldCollectionMappingGrid],
                        scrollable: true,
                        top: "10px"
                    });
                },
                name: "collection_mapping_panel"
            });
            collectionPanel.add(this.fieldCollectionMappingPanel);
            this.getFieldCollectionAttributes();
        },

        resetMappingPanel: function () {
            this.getFileInfo();
        },

        // Get mapping configuration
        getFileInfo: function () {
            var m = this.getValues();
            Ext.Ajax.request({
                url: "/DataConnect/mapping/get-mapping-info",
                params: {
                    name: this.data.name,
                    classId: m.className,
                    method: "post"
                },
                success: this.getFileInfoComplete.bind(this)
            });
        },

        getFileInfoComplete: function (response) {
            var data = Ext.decode(response.responseText);
            if (data.success) {
                this.showDataWindow(data);
            } else {
                pimcore.helpers.showNotification(t('error'), t('error_info_msg'), 'error');
            }
        },

        //it will gives the show grid for mapping
        showDataWindow: function (data) {
            var allowedClasses = [];
            var m = this.getValues();
            this.mappingData = data;
            var that = this;
            // Mapping configuration data
            this.mappingStore = new Ext.data.Store({
                autoDestroy: true,
                data: data,
                proxy: {
                    type: 'memory',
                    reader: {
                        type: 'json',
                        rootProperty: 'mappingStore'
                    }
                },
                fields: ["source", "firstRow", "target", "config", "ref_field"]
            });

            var targetFields = data.targetFields;
            targetFields.push(["", t("-ignore-")]);
            targetFields.push(["objectbrick", t("objectbrick")]);
            targetFields.push(["fieldcollection", t("fieldcollection")]);

            targetFields = new Ext.data.ArrayStore({
                data: targetFields,
                fields: ['value', 'text']
            });

            targetFields.sort('text', 'ASC');

            if (this.data.allowedClasses != null) {

                this.allowedClassesStore = new Ext.data.ArrayStore({
                    data: this.data.allowedClasses,
                    fields: ['field2', 'field1']
                });

            } else {
                Ext.Ajax.request({
                    url: "/DataConnect/connection/get-allowed-classes",
                    params: {
                        classId: m.className,
                        conName: this.data.name
                    },
                    method: 'post',
                    async: false,
                    success: function (response, xhr) {
                        var res = Ext.decode(response.responseText);
                        if (res.success) {
                            this.allowedClassesStore = new Ext.data.ArrayStore({
                                data: res.class,
                                fields: ['field2', 'field1']
                            });
                        } else {
                            pimcore.helpers.showNotification(t('error'), t('error_saving_msg'), 'error');
                        }
                    }
                })
            }

            this.cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
                clicksToEdit: 1
            });

            this.mappingGrid = new Ext.grid.Panel({
                store: this.mappingStore,
                plugins: [this.cellEditing],
                columns: [
                    {
                        header: t('source'),
                        sortable: false,
                        dataIndex: "source",
                        renderer: function (value, p, r) {
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
                            queryMode: 'local',
                            triggerAction: "all",
                            valueField: 'value',
                            displayField: 'text'
                        })
                    },
                    {
                        header: t('config'),
                        sortable: false,
                        dataIndex: "ref_class",
                        name: 'ref_class',
                        flex: 1,
                        editor: new Ext.form.ComboBox({
                            store: this.allowedClassesStore,
                            mode: "local",
                            queryMode: 'local',
                            listeners: {
                                select: function (combo, record, index) {
                                    record.set('ref_field', '');
                                }
                            },
                            valueField: 'field2',
                            displayField: 'field1'
                        })
                    },
                    {
                        header: t('config_field'),
                        sortable: false,
                        dataIndex: "ref_field",
                        flex: 1,
                        editor: new Ext.form.ComboBox({
                            store: "",
                            queryMode: "local",
                            autoSelect: true,
                            editable: true,
                            listeners: {
                                focus: function (combo, record, index) {
                                    var grid = Ext.get("coloumnmappingGrid_" + that.data.name);
                                    grid = grid.component;
                                    var store = grid.getStore();
                                    var selectedRecord = grid.getSelectionModel().getSelection()[0];
                                    var rowIndex = selectedRecord.data.index;
                                    var selectedString = store.data.items[rowIndex].data.ref_class;

                                    if (selectedString) {
                                        var dependantStore = new Ext.data.JsonStore({
                                            id: "depandentstore_" + that.data.name,
                                            proxy: {
                                                type: 'ajax',
                                                url: '/DataConnect/mapping/get-dependant-class-fields',
                                                extraParams: {
                                                    classValue: selectedString
                                                },
                                                reader: {
                                                    type: 'json',
                                                    rootProperty: 'fields'
                                                }
                                            },
                                            fields: ["value", "text"],
                                            sorters: [{
                                                property: 'text',
                                                direction: 'ASC'
                                            }]
                                        });
                                        dependantStore.load();
                                        combo.setStore(dependantStore);
                                    } else {
                                        combo.setStore(new Ext.data.JsonStore({}));
                                    }
                                },
                                render: function (combo, record, index) {
                                    if (combo.getValue() === null) {
                                        combo.setValue("-ignore-");
                                    }
                                }
                            },
                            triggerAction: "all",
                            valueField: 'value',
                            displayField: 'text'
                        })

                    }
                ],
                forceFit: true,
                style: "margin-left: 10px;margin-top: 10px;margin-right:10px;"
            });

            //Add Mapping panel into accordian
            if (this.mappingPanel.items.length < 1) {
                this.mappingPanel.add(this.mappingGrid);
            } else {
                this.mappingPanel.remove(this.mappingPanel.items.items[0]);
                this.mappingPanel.add(this.mappingGrid);
            }

            Ext.getCmp('panel1' + this.data.name).collapse();
            Ext.getCmp('panel2' + this.data.name).expand();
        },

        //this will get the data which is to be mapped in simple mapping
        getMapping: function () {
            var mappingData = [];
            var m = this.getValues();
            this.mappingGrid.getStore().each(function (record) {
                var tmData = [];
                var keys = Object.keys(record.data);
                for (var u = 1; u < keys.length; u++) {
                    tmData.push(record.data[keys[u]]);
                }

                mappingData.push(Ext.encode(tmData));
            });

            Ext.Ajax.request({
                url: "/DataConnect/mapping/save-mapping-data",
                params: {
                    classId: m.className,
                    conName: this.data.name,
                    mappingData: Ext.encode(mappingData)
                },
                method: 'post',
                success: function (response, xhr) {
                    var res = Ext.decode(response.responseText);
                    if (res.success) {
                        pimcore.helpers.showNotification(t('success'), t('column_mapping_save_msg'), 'success');
                    } else {
                        pimcore.helpers.showNotification(t('error'), t('error_saving_msg'), 'error');
                    }
                }
            });
            this.getBrickMappingAttributes();
        },

        //this will get the class attributes of brick type
        getBrickMappingAttributes: function () {
            Ext.getCmp('panel2' + this.data.name).collapse();
            Ext.getCmp('panel3' + this.data.name).expand();

            var m = this.getValues();
            Ext.Ajax.request({
                url: '/DataConnect/mapping/get-brick-attributes',
                params: {
                    classId: m.className,
                    con: this.data.name,
                    method: "post"
                },
                success: this.getCompleteDataForBrick.bind(this)
            });
        },

        //Handle data on success
        getCompleteDataForBrick: function (response) {
            var data = Ext.decode(response.responseText);
            if (data.success) {
                this.brickMapping(data);
            } else {
                pimcore.helpers.showNotification(t('error'), t('error_info_msg'), 'error');
            }
        },

        //this will show the brick grid for mapping
        brickMapping: function (data) {
            this.emptyBrickButtons = "";
            var storeValue = [];
            var BrickMappingStore = new Ext.data.Store({
                autoDestroy: true,
                data: data,
                proxy: {
                    type: 'memory',
                    reader: {
                        type: 'json',
                        rootProperty: 'brickStore'
                    }
                },
                fields: ["text"]
            });

            for (var i = 0; i < BrickMappingStore.data.items.length; i++) {
                if (BrickMappingStore.data.items[i].data.text != "") {
                    storeValue.push(BrickMappingStore.data.items[i].data.text);
                }
            }

            var bAtr = [];
            var res = this.getSelectedBrickAttr(this.data.name);
            bAtr.push(res);

            this.objectBrickPanel = new Ext.form.FormPanel({
                items: [{
                    xtype: "fieldset",
                    itemId: "generalFieldset",
                    title: "Object-Brick Mapping",
                    collapsible: false,
                    defaults: {
                        width: 400
                    },
                    items: [
                        {
                            xtype: 'multiselect',
                            fieldLabel: 'Multiselect',
                            name: 'multiselect',
                            store: BrickMappingStore,
                            valueField: "text",
                            displayField: "text",
                            multiSelect: true,
                            value: bAtr.join(','),
                            width: 400,
                            validateOnChange: true,
                            listeners: {
                                change: function (thaat, value, oldValue, e) {
                                    this.getBrickGrid(value);
                                }.bind(this)
                            } // listeners
                        }
                    ]
                }]
            });

            if (this.brickMappingPanel.items.length < 1) {
                if (BrickMappingStore.data.items[0].data.text != "") {
                    if (bAtr.length > 0 && data.brickStore.length) {
                        this.getBrickGrid(bAtr);
                    }
                    this.brickMappingPanel.add(this.objectBrickPanel);
                } else {
                    this.brickMappingPanel.remove(this.brickMappingPanel.items.items[0]);
                    this.brickMappingPanel.remove(this.brickMappingPanel.items.items[1]);
                    this.emptyBrickButtons = "brick";
                    this.getEmptyPanel('ObjectBrick');
                }
            } else {
                if (BrickMappingStore.data.items[0].data.text != "") {
                    this.brickMappingPanel.remove(this.brickMappingPanel.items.items[1]);
                    this.brickMappingPanel.remove(this.brickMappingPanel.items.items[0]);
                    if (bAtr.length > 0 && data.brickStore.length) {
                        this.getBrickGrid(bAtr);
                    }
                    this.brickMappingPanel.add(this.objectBrickPanel);
                } else {
                    this.brickMappingPanel.remove(this.brickMappingPanel.items.items[0]);
                    this.brickMappingPanel.remove(this.brickMappingPanel.items.items[1]);
                    this.emptyBrickButtons = "brick";
                    this.getEmptyPanel('ObjectBrick');
                }
            }
        },

        getSelectedBrickAttr: function (conName) {
            var blank = [];
            var param;
            if (conName) {
                Ext.Ajax.request({
                    url: "/DataConnect/mapping/get-preselected-brick-attr",
                    params: {
                        con: conName
                    },
                    method: 'get',
                    async: false,
                    success: function (response, xhr) {
                        var result = Ext.decode(response.responseText);
                        if (result.success == true) {
                            param = result.data[0]['selected_attr'];

                        } else {
                            param = blank;
                        }
                    }
                });
            }
            return param;
        },

        //this will get the data in target column for brick
        getBrickGrid: function (myVal) {
            var m = this.getValues();
            var that = this;
            var brickAttr = "";
            Ext.each(myVal, function (val, index) {
                if (brickAttr == "") {
                    brickAttr = val;
                } else {
                    brickAttr = brickAttr + "," + val;
                }
            });
            if (myVal != "") {
                Ext.Ajax.request({
                    url: "/DataConnect/mapping/get-advanced-grid-mapping",
                    params: {
                        con: this.data.name,
                        classId: m.className,
                        selectedAttr: brickAttr,
                        type: 'objectbrick'
                    },
                    method: 'get',
                    success: function (response, xhr) {
                        var result = Ext.decode(response.responseText);
                        that.makeObjectBrickGrid(result, brickAttr);
                    }
                });

            } else {
                //Ext.MessageBox.alert("Alert", "Please select a brick to get its attributes");
            }
        },

        //this will add the data in grid and form the grid by store
        makeObjectBrickGrid: function (data, myVal) {
            this.brickVal = "";

            var that = this;
            var targetFields = [];
            //create advanced mapping store
            var advancedMappingStore = new Ext.data.Store({
                autoDestroy: true,
                data: data,
                proxy: {
                    type: 'memory',
                    reader: {
                        type: 'json',
                        rootProperty: 'mappingStore'
                    }
                },
                fields: ["source", "firstRow", "target", "config", "brick_ref_field"]
            });

            targetFields = data.targetFields;
            targetFields.push(["", t("-ignore-")]);
            targetFields = new Ext.data.ArrayStore({
                data: targetFields,
                fields: ['value', 'text']
            });
            targetFields.sort('text', 'ASC');

            this.cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
                clicksToEdit: 1
            });

            var brickallowedClasses = data.brickAllowedAttr;

            this.brickVal = myVal;


            this.advancedMappingGrid = new Ext.grid.Panel({
                name: "object_brick_grid",
                store: advancedMappingStore,
                plugins: [this.cellEditing],
                columns: [
                    {
                        header: t('source'),
                        sortable: false,
                        dataIndex: "source",
                        renderer: function (value, p, r) {
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
                            queryMode: 'local',
                            valueField: 'value',
                            displayField: 'text'
                        })
                    },
                    {
                        header: t('config'),
                        sortable: false,
                        dataIndex: "ref_class",
                        flex: 1,
                        editor: new Ext.form.ComboBox({
                            store: brickallowedClasses,
                            mode: "local",
                            queryMode: 'local',
                            listeners: {
                                select: function (combo, record, index) {
                                    record.set('brick_ref_field', '');
                                }
                            },
                            triggerAction: "all",
                            valueField: 'value',
                            displayField: 'text'
                        })
                    },
                    {
                        header: t('config_field'),
                        sortable: false,
                        dataIndex: "brick_ref_field",
                        flex: 1,
                        editor: new Ext.form.ComboBox({
                            store: '',
                            queryMode: "local",
                            autoSelect: true,
                            editable: false,
                            listeners: {
                                focus: function (combo) {
                                    var grid = Ext.get("brickmappingGrid_" + that.data.name);
                                    grid = grid.component;
                                    var store = grid.getStore();
                                    var selectedRecord = grid.getSelectionModel().getSelection()[0];
                                    var rowIndex = selectedRecord.data.index;
                                    var selectedString = store.data.items[rowIndex].data.ref_class;
                                    if (selectedString) {
                                        var brckdependantStore = new Ext.data.JsonStore({
                                            id: "brickdepandentstore_" + that.data.name,
                                            proxy: {
                                                type: 'ajax',
                                                url: '/DataConnect/mapping/get-brick-dependant-class-fields',
                                                method: 'post',
                                                extraParams: {
                                                    classValue: selectedString
                                                },
                                                reader: {
                                                    type: 'json',
                                                    rootProperty: 'fields'
                                                }
                                            },
                                            fields: ["value", "text"],
                                            sorters: [{
                                                property: 'text',
                                                direction: 'ASC'
                                            }]
                                        });
                                        brckdependantStore.load();
                                        combo.setStore(brckdependantStore);
                                    } else {
                                        combo.setStore(new Ext.data.JsonStore({}));
                                    }
                                },
                                render: function (combo, record, index) {
                                    if (combo.getValue() === null) {
                                        combo.setValue("-ignore-");
                                    }
                                }
                            },
                            triggerAction: "all",
                            valueField: 'value',
                            displayField: 'text'
                        })

                    }
                ],
                forceFit: true
            });

            if (this.brickMappingPanel.items.length == 1) {
                this.brickMappingPanel.add(this.advancedMappingGrid);

            }
        },

        // this function will save the mapping data of brick
        getAdvancedMappingData: function () {
            var mappingData = [];
            var m = this.getValues();

            if (this.advancedMappingGrid) {
                if (this.advancedMappingGrid.getStore()) {
                    this.advancedMappingGrid.getStore().each(function (record) {
                        var tmData = [];
                        var keys = Object.keys(record.data);
                        for (var u = 1; u < keys.length; u++) {
                            tmData.push(record.data[keys[u]]);
                        }

                        mappingData.push(Ext.encode(tmData));
                    });
                }
            }
            Ext.Ajax.request({
                url: "/DataConnect/mapping/save-advanced-mapping-data",
                params: {
                    classId: m.className,
                    conName: this.data.name,
                    mappingData: Ext.encode(mappingData),
                    selectedAttr: this.brickVal,
                    colType: 'objectbrick'
                },
                method: 'post',
                success: function (response, xhr) {
                    var res = Ext.decode(response.responseText);
                    if (res.success) {
                        pimcore.helpers.showNotification(t('success'), t('brick_mapping_save_msg'), 'success');
                    } else {
                        pimcore.helpers.showNotification(t('error'), t('error_saving_msg'), 'error');
                    }
                }
            });
            this.getFieldCollectionAttributes();
        },

        callNextPanel: function (value) {
            if (value == 'ObjectBrick') {
                this.getFieldCollectionAttributes();
            } else if (value == 'FieldCollection') {
                this.getDataForSettingForm();
            }
        },

        // if no brick or fielcollection of class is present then this function will be called
        getEmptyPanel: function (val) {

            this.emptyPanel = new Ext.form.FormPanel({
                //id: val,
                items: [{
                    xtype: "fieldset",
                    itemId: "generalFieldset",
                    title: val + "Mapping",
                    collapsible: false,
                    items: [
                        {
                            xtype: 'label',
                            name: "fetch_" + val,
                            id: "fetch_" + val + this.data.name + "_id2",
                            text: t(val + '_msg'),
                        }
                    ]
                }]
            });

            if (val == 'ObjectBrick') {
                this.brickMappingPanel.add(this.emptyPanel);
            } else {
                this.fieldCollectionMappingPanel.add(this.emptyPanel);
            }

        },

        //this will get the class attribute of fieldcollection type
        getFieldCollectionAttributes: function () {
            Ext.getCmp('panel3' + this.data.name).collapse();
            Ext.getCmp('panel4' + this.data.name).expand();
            var m = this.getValues();
            Ext.Ajax.request({
                url: '/DataConnect/mapping/get-field-collection-attributes',
                params: {
                    classId: m.className,
                    method: "post",
                },
                success: this.getCompleteDataForFieldCollection.bind(this)
            });
        },

        getCompleteDataForFieldCollection: function (response) {
            var data = Ext.decode(response.responseText);
            if (data.success) {
                this.fieldCollectionMapping(data);
            } else {
                pimcore.helpers.showNotification(t('error'), t('error_info_msg'), 'error');
            }
        },

        //this will show the field collection mapping
        fieldCollectionMapping: function (data) {
            this.emptyCollectionButtons = "";
            var storeValue = [];
            var FieldCollectionMappingStore = new Ext.data.Store({
                autoDestroy: true,
                data: data,
                proxy: {
                    type: 'memory',
                    reader: {
                        type: 'json',
                        rootProperty: 'fieldcollectionStore'
                    }
                },
                fields: ["text"]
            });
            for (var i = 0; i < FieldCollectionMappingStore.data.items; i++) {
                if (FieldCollectionMappingStore.data.items[0].data.text != "") {
                    storeValue.push(FieldCollectionMappingStore.data.items[0].data.text);
                }
            }

            var fAtr = [];
            var res = this.getSelectedFieldCollectionAttr(this.data.name);
            fAtr.push(res);

            this.fieldCollectionPanel = new Ext.form.FormPanel({
                items: [{
                    xtype: "fieldset",
                    itemId: "generalFieldset",
                    title: "Field Collection Mapping",
                    collapsible: false,
                    defaults: {
                        width: 400
                    },
                    items: [
                        {
                            xtype: 'multiselect',
                            fieldLabel: 'Multiselect',
                            name: 'multiselect',
                            store: FieldCollectionMappingStore,
                            valueField: "text",
                            displayField: "text",
                            multiSelect: true,
                            value: fAtr.join(","), //storeValue.join(","),
                            width: 400,
                            listeners: {
                                change: function (that, value, oldValue, e) {
                                    this.getFieldCollectionGrid(value);
                                }.bind(this)

                            }
                        }]
                }]
            });


            if (this.fieldCollectionMappingPanel.items.length < 1) {
                if (FieldCollectionMappingStore.data.items[0].data.text != "") {
                    if (fAtr.length > 0 && data.fieldcollectionStore.length) {
                        this.getFieldCollectionGrid(fAtr);
                    }
                    this.fieldCollectionMappingPanel.add(this.fieldCollectionPanel);
                } else {
                    this.fieldCollectionMappingPanel.remove(this.fieldCollectionMappingPanel.items.items[0]);
                    this.fieldCollectionMappingPanel.remove(this.fieldCollectionMappingPanel.items.items[1]);
                    this.emptyCollectionButtons = "collection";
                    this.getEmptyPanel('FieldCollection');
                }
            } else {
                if (FieldCollectionMappingStore.data.items[0].data.text != "") {
                    this.fieldCollectionMappingPanel.remove(this.fieldCollectionMappingPanel.items.items[1]);
                    this.fieldCollectionMappingPanel.remove(this.fieldCollectionMappingPanel.items.items[0]);
                    if (fAtr.length > 0 && data.fieldcollectionStore.length) {
                        this.getFieldCollectionGrid(fAtr);
                    }
                    this.fieldCollectionMappingPanel.add(this.fieldCollectionPanel);
                } else {
                    this.fieldCollectionMappingPanel.remove(this.fieldCollectionMappingPanel.items.items[0]);
                    this.fieldCollectionMappingPanel.remove(this.fieldCollectionMappingPanel.items.items[1]);
                    this.emptyCollectionButtons = "collection";
                    this.getEmptyPanel('FieldCollection');
                }
            }
        },

        getSelectedFieldCollectionAttr: function (conName) {
            var blank = [];
            var param;
            if (conName) {
                Ext.Ajax.request({
                    url: "/DataConnect/mapping/get-preselected-field-collection-attr",
                    params: {
                        con: conName,
                    },
                    method: 'get',
                    async: false,
                    success: function (response, xhr) {
                        var result = Ext.decode(response.responseText);
                        if (result.success == true) {
                            param = result.data[0]['selected_attr'];

                        } else {
                            param = blank;
                        }
                    }
                });
            }
            return param;

        },

        //this will get the fieldcollection grid data
        getFieldCollectionGrid: function (myVal) {
            var m = this.getValues();
            var that = this;
            var fcAttr = "";
            Ext.each(myVal, function (val, index) {
                if (fcAttr == "") {
                    fcAttr = val;
                } else {
                    fcAttr = fcAttr + "," + val;
                }
            });
            if (myVal != "") {
                Ext.Ajax.request({
                    url: "/DataConnect/mapping/get-advanced-grid-mapping",
                    params: {
                        con: this.data.name,
                        classId: m.className,
                        selectedAttr: fcAttr,
                        type: 'fieldcollection'
                    },
                    method: 'get',
                    success: function (response, xhr) {
                        var result = Ext.decode(response.responseText);
                        that.makeFieldCollectionGrid(result, fcAttr);
                    }
                });
            } else {
                //Ext.MessageBox.alert("Alert", "Please select a fieldcollection to get its attributes")
            }
        },

        // this will add the data to the grid for target coloumn
        makeFieldCollectionGrid: function (data, myval) {
            this.collectionVal = "";
            var fieldCollectionMappingStore = new Ext.data.Store({
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
            targetFields.push(["", t("-ignore-")]);
            targetFields = new Ext.data.ArrayStore({
                data: targetFields,
                fields: ['value', 'text']
            });
            targetFields.sort('text', 'ASC');

            this.cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
                clicksToEdit: 1
            });


            this.collectionVal = myval;

            this.fieldCollectionMappingGrid = new Ext.grid.Panel({
                name: "field_collection_grid",
                store: fieldCollectionMappingStore,
                plugins: [this.cellEditing],
                columns: [
                    {
                        header: t('source'),
                        sortable: false,
                        dataIndex: "source",
                        renderer: function (value, p, r) {
                            return r.data.source + " (" + r.data.firstRow + ")";
                        }.bind(this),
                        flex: 1
                    },
                    {
                        header: t('target'),
                        sortable: false,
                        dataIndex: "target",
                        flex: 2,
                        editor: new Ext.form.ComboBox({
                            store: targetFields,
                            queryMode: 'local',
                            valueField: 'value',
                            displayField: 'text'
                        })
                    },
                    {
                        header: t('config'),
                        sortable: false,
                        dataIndex: "ref_class",
                        editor: 'textfield',
                        flex: 1
                    },
                    {
                        header: t('group'),
                        sortable: false,
                        dataIndex: "group",
                        editor: 'textfield',
                        flex: 1
                    }
                ],
                forceFit: true
            });
            if (this.fieldCollectionMappingPanel.items.length == 1) {
                this.fieldCollectionMappingPanel.add(this.fieldCollectionMappingGrid);
            }
        },

        //this will save the field collection mapped data
        getfieldCollectionMappedData: function () {
            var mappingData = [];
            var m = this.getValues();
            if (this.fieldCollectionMappingGrid) {
                this.fieldCollectionMappingGrid.getStore().each(function (record) {
                    var tmData = [];
                    var keys = Object.keys(record.data);
                    for (var u = 1; u < keys.length; u++) {
                        tmData.push(record.data[keys[u]]);
                    }
                    mappingData.push(Ext.encode(tmData));
                });
                Ext.Ajax.request({
                    url: "/DataConnect/mapping/save-advanced-mapping-data",
                    params: {
                        classId: m.className,
                        conName: this.data.name,
                        mappingData: Ext.encode(mappingData),
                        colType: 'fieldcollection',
                        selectedAttr: this.collectionVal
                    },
                    method: 'post',
                    success: function (response, xhr) {
                        var res = Ext.decode(response.responseText);
                        if (res.success) {
                            pimcore.helpers.showNotification(t('success'), t('fieldcollection_mapping_save_msg'), 'success');
                        } else {
                            pimcore.helpers.showNotification(t('error'), t('error_saving_msg'), 'error');
                        }
                    }
                });
            }
            this.getDataForSettingForm();
        },

        //this will leave the unmapped data
        getDataForSettingForm: function () {
            var that = this;
            Ext.Ajax.request({
                url: "/DataConnect/mapping/get-setting-panel-data",
                params: {
                    conName: this.data.name,
                },
                method: 'get',
                success: function (response, xhr) {
                    var res = Ext.decode(response.responseText);
                    if (res.success) {
                        that.getsettingForm(res);
                    } else {
                        pimcore.helpers.showNotification(t('error'), t('error_saving_msg'), 'error');
                    }
                }
            });

        },

        // this will show the setting form in setting panel
        getsettingForm: function (myVal) {
            Ext.getCmp('panel3' + this.data.name).collapse();
            Ext.getCmp('panel5' + this.data.name).expand();

            var data = myVal;
            var sourceFields = [];
            for (i = 0; i < data.cols; i++) {
                sourceFields.push([data.settingData[i].firstRow, data.settingData[i].firstRow]);
            }


            var filenameMappingStore = sourceFields;

            this.settingsFormPanel = new Ext.form.FormPanel({
                items: [{
                    xtype: "fieldset",
                    itemId: "settingFieldset",
                    title: "Settings",
                    collapsible: false,
                    defaults: {
                        width: 80,
                    },
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
                            hidden: true,
                            listeners: {
                                render: function (el) {
                                    Ext.create('Ext.tip.ToolTip', {
                                        target: el.getEl(),
                                        html: el.tip
                                    });
                                }
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
                            editable: false,
                            listeners: {
                                render: function (el) {
                                    Ext.create('Ext.tip.ToolTip', {
                                        target: el.getEl(),
                                        html: el.tip
                                    });
                                }
                            }
                        },
                        {
                            xtype: 'fieldcontainer',
                            fieldLabel: t('overwrite_label'),
                            defaultType: 'checkboxfield',
                            width: 500,
                            tip: t('overwrite_checkbox'),
                            listeners: {
                                render: function (el) {
                                    Ext.create('Ext.tip.ToolTip', {
                                        target: el.getEl(),
                                        html: el.tip
                                    });
                                }
                            },
                            items: [
                                {
                                    name: 'overwrite',
                                    inputValue: 1,
                                    checked: (data.overWrite == 'yes') ? true : false
                                }
                            ]
                        },
                        {
                            xtype: 'button',
                            name: "reset_button",
                            id: "setting_reset" + this.data.name,
                            style: "float:right",
                            text: 'Reload',
                            width: 90,
                            iconCls: "pimcore_icon_reload",
                            handler: this.resetSettingPanel.bind(this)
                        },
                        {
                            xtype: 'button',
                            name: "setting_button",
                            id: "setting_button_id" + this.data.name,
                            style: "float:right;margin-right:10px",
                            text: t('save'),
                            iconCls: "pimcore_icon_apply",
                            handler: this.saveSettingPanelData.bind(this)
                        }
                    ]
                }]
            });
            if (this.settingMappingPanel.items.length < 1) {
                this.settingMappingPanel.add(this.settingsFormPanel);
            } else {
                this.settingMappingPanel.remove(this.settingMappingPanel.items.items[0]);
                this.settingMappingPanel.add(this.settingsFormPanel);
            }

        },

        // this will save the setting form data
        saveSettingPanelData: function (action) {
            //This will save file import mapping information
            var formData = this.settingsFormPanel.getForm().getFieldValues();
            var keyName = '';
            var objkeyname = '';
            // get mapping
            var m = this.getValues();
            if (formData.keyname != 'undefined') {
                keyName = formData.keyname;
            } else {
                keyName = '';
            }
            if (formData.objkeyname != 'undefined') {
                objkeyname = formData.objkeyname;
            } else {
                objkeyname = '';
            }

            if (this.settingsFormPanel.getForm().isValid() && keyName != null) {
                Ext.Ajax.request({
                    url: "/DataConnect/mapping/save-mapping-data",
                    params: {
                        classId: m.className,
                        conName: this.data.name,
                        keyName: keyName,
                        overWrite: formData.overwrite,
                        objKeyName: objkeyname,
                    },
                    method: 'post',
                    success: function (response, xhr) {
                        var res = Ext.decode(response.responseText);
                        if (res.success) {
                            pimcore.helpers.showNotification(t('success'), t('settings_save_msg'), 'success');

                        } else {
                            pimcore.helpers.showNotification(t('error'), t('error_saving_msg'), 'error');
                        }
                    }
                });
            } else {
                Ext.Msg.alert(t('error'), t('invalid_mapping_msg'));
            }
        },

        // general definition form
        getGeneralDefinitionPanel: function () {
            var language = [];
            var checkbox = false;
            for (i = 0; i < this.data.langConfg.length; i++) {
                language.push([this.data.langConfg[i],
                    this.data.langConfg[i]]);
            }

            var classStore = pimcore.globalmanager.get("object_types_store");

            this.generalDefinitionForm = new Ext.form.FormPanel({
                border: false,
                items: [
                    {
                        xtype: "fieldset",
                        itemId: "generalFieldset",
                        title: t('general_def_title'),
                        collapsible: true,
                        defaults: {
                            width: 450,
                            labelWidth: 180
                        },
                        items: [{
                            xtype: "textfield",
                            name: "name",
                            value: this.data.name,
                            fieldLabel: t("name") + '<span class="req" style="color:red">*</span>',
                            disabled: true,
                            allowBlank: false
                        }, {
                            xtype: "combo",
                            name: "className",
                            store: classStore,
                            id: "classNameCombo" + this.data.name,
                            mode: "local",
                            triggerAction: "all",
                            fieldLabel: 'Class Name<span class="req" style="color:red">*</span>',
                            allowBlank: false,
                            editable: false,
                            tip: t('target_class_tooltip'),
                            value: this.data.className,
                            valueField: "id",
                            listeners: {
                                render: function (el) {
                                    Ext.create('Ext.tip.ToolTip', {
                                        target: el.getEl(),
                                        html: el.tip
                                    });
                                }
                            }
                        }, {
                            name: 'importPath',
                            fieldLabel: 'Import File Path<span class="req" style="color:red">*</span>',
                            fieldCls: 'pimcore_droptarget_input',
                            id: 'importFilePathTextField' + this.data.name,
                            value: this.data.importPath,
                            xtype: 'textfield',
                            allowBlank: false,
                            enableKeyEvents: true,
                            tip: t('import_path_tooltip'),
                            listeners: {
                                render: function (el) {
                                    Ext.create('Ext.tip.ToolTip', {
                                        target: el.getEl(),
                                        html: el.tip
                                    });
                                    new Ext.dd.DropZone(el.getEl(), {
                                        reference: this,
                                        ddGroup: 'element',
                                        getTargetFromEvent: function (e) {
                                            return this.getEl();
                                        }.bind(el),
                                        onNodeOver: function (target, dd, e, data) {
                                            data = data.records[0].data;
                                            if (data.elementType == 'asset' && data.type == 'folder') {
                                                return Ext.dd.DropZone.prototype.dropAllowed;
                                            }

                                            return Ext.dd.DropZone.prototype.dropNotAllowed;
                                        },
                                        onNodeDrop: function (target, dd, e, data) {
                                            data = data.records[0].data;
                                            if (data.elementType == 'asset' && data.type == 'folder') {
                                                this.setValue(data.path);
                                                return true;
                                            }
                                            return false;
                                        }.bind(el)
                                    });
                                },
                                keyup: function (el) {
                                    el.setValue('');
                                }
                            }
                        }, {
                            xtype: "combo",
                            name: "language",
                            store: language,
                            id: 'languageCombo' + this.data.name,
                            mode: "local",
                            triggerAction: "all",
                            fieldLabel: 'Language<span class="req" style="color:red">*</span>',
//                                    value: this.data.language_key,
                            value: "en",
                            hidden: true,
                            allowBlank: false,
                            editable: false,
                            tip: t('default_lang_tooltip'),
                            listeners: {
                                render: function (el) {
                                    Ext.create('Ext.tip.ToolTip', {
                                        target: el.getEl(),
                                        html: el.tip
                                    });
                                }
                            }
                        }, {
                            fieldLabel: 'TargetPath<span class="req" style="color:red">*</span>',
                            name: 'targetPath',
                            fieldLabel: 'Target Path<span class="req" style="color:red">*</span>',
                            fieldCls: 'pimcore_droptarget_input',
                            id: 'targetPathTextField' + this.data.name,
                            value: this.data.target_path,
                            allowBlank: false,
                            xtype: 'textfield',
                            allowBlank: false,
                            enableKeyEvents: true,
                            tip: t('target_path_tooltip'),
                            listeners: {
                                render: function (el) {
                                    Ext.create('Ext.tip.ToolTip', {
                                        target: el.getEl(),
                                        html: el.tip
                                    });
                                    new Ext.dd.DropZone(el.getEl(), {
                                        reference: this,
                                        ddGroup: 'element',
                                        getTargetFromEvent: function (e) {
                                            return this.getEl();
                                        }.bind(el),
                                        onNodeOver: function (target, dd, e, data) {
                                            data = data.records[0].data;

                                            if (data.elementType == 'object') {
                                                return Ext.dd.DropZone.prototype.dropAllowed;
                                            }

                                            return Ext.dd.DropZone.prototype.dropNotAllowed;
                                        },
                                        onNodeDrop: function (target, dd, e, data) {
                                            data = data.records[0].data;

                                            if (data.elementType == 'object') {

                                                this.setValue(data.path);
                                                return true;
                                            }

                                            return false;
                                        }.bind(el)
                                    });
                                },
                                keyup: function (el) {
                                    el.setValue('');
                                }
                            }
                        },
                            {
                                xtype: "checkbox",
                                name: "logs",
                                id: 'logsCombo' + this.data.name,
                                mode: "local",
                                triggerAction: "all",
                                fieldLabel: t('Logs'),
                                value: this.data.logs,
                                tip: t('Logs_tooltip'),
                                listeners: {
                                    render: function (el) {
                                        Ext.create('Ext.tip.ToolTip', {
                                            target: el.getEl(),
                                            html: el.tip
                                        });
                                    }
                                }
                            },
                            {
                                xtype: "checkbox",
                                name: "active",
                                id: 'activeCombo' + this.data.name,
                                mode: "local",
                                triggerAction: "all",
                                fieldLabel: 'Active',
                                value: checkbox,
                                allowBlank: true,
                                tip: t('active_tooltip'),
                                listeners: {
                                    render: function (el) {
                                        Ext.create('Ext.tip.ToolTip', {
                                            target: el.getEl(),
                                            html: el.tip
                                        });
                                    }
                                }
                            }


                        ]
                    }]
            });
            return this.generalDefinitionForm;
        },

        // source definition
        getSourceDefinitionPanel: function () {
            this.sourceDefinitionsItems = new Ext.Panel({
                style: "margin-bottom: 20px",
                items: [this.getAddControl()]
            });
            this.sourceDefinitionFieldset = new Ext.form.FieldSet({
                itemId: "sourcedefinitionFieldsetId",
                title: t('source_definition_title'),
                style: "margin-top: 20px;margin-bottom: 20px ; height : 200px",
                collapsible: true,
                items: [this.sourceDefinitionsItems, {
                    xtype: "displayfield",
                    name: "errorMessage",
                    itemId: "errorMessage",
                    fieldStyle: "color: red;"
                },
                    {
                        xtype: "displayfield",
                        name: "totalRec",
                        itemId: "totalRec",
                        fieldStyle: "color: black;"
                    }]
            });
            if (this.data.dataSourceConfig) {
                for (var i = 0; i < this.data.dataSourceConfig.length; i++) {
                    if (this.data.dataSourceConfig[i]) {
                        this.addSourceDefinition(this.data.dataSourceConfig[i]);
                    }
                }
            }
            return this.sourceDefinitionFieldset;
        },

        // delete source handling
        getDeleteControl: function (title, index) {
            var items = [{
                xtype: 'tbtext',
                text: title
            }];
            items.push({
                cls: "pimcore_block_button_minus",
                iconCls: "pimcore_icon_minus",
                listeners: {
                    "click": this.removeSourceDefinition.bind(this, index)
                }
            });
            var toolbar = new Ext.Toolbar({
                items: items
            });
            return toolbar;
        },

        // remove source
        removeSourceDefinition: function (key) {
            for (var i = 0; i < this.currentElements.length; i++) {
                if (this.currentElements[i].key == key) {
                    this.currentElements[i].deleted = true;
                    this.sourceDefinitionsItems
                        .remove(this.currentElements[i].adapter
                            .getElement());
                }
            }
            this.currentElementCount--;
            this.sourceDefinitionsItems.remove(this.sourceDefinitionsItems
                .getComponent(0));
            this.sourceDefinitionsItems.insert(0, this.getAddControl());
            this.sourceDefinitionsItems.updateLayout();
        },

        // add source
        getAddControl: function () {
            var classMenu = [];
            if (this.currentElementCount < 1) {
                classMenu.push({
                    text: t('custom_report_adapter_csv'),
                    handler: this.addSourceDefinition.bind(this, {
                        type: 'csv'
                    }),
                    iconCls: "pimcore_icon_objectbricks"
                });
            }
            var items = [];
            if (classMenu.length == 1) {
                items.push({
                    cls: "pimcore_block_button_plus",
                    text: ts(classMenu[0].text),
                    iconCls: "pimcore_icon_plus",
                    handler: classMenu[0].handler
                });
            } else if (classMenu.length > 1) {
                items.push({
                    cls: "pimcore_block_button_plus",
                    iconCls: "pimcore_icon_plus",
                    menu: classMenu
                });
            } else {
                items.push({
                    xtype: "tbtext",
                    text: t('source_limit_msg')
                });
            }
            var toolbar = new Ext.Toolbar({
                items: items
            });
            return toolbar;
        },

        // add source definition
        addSourceDefinition: function (sourceDefinitionData) {
            this.sourceDefinitionsItems.remove(this.sourceDefinitionsItems.getComponent(0));
            var currentData = {};
            if (!this.currentElements) {
                this.currentElements = [];
            }
            var key = this.currentElements.length;
            sourceDefinitionData.type = sourceDefinitionData.type ? sourceDefinitionData.type : '';
            if (sourceDefinitionData.type != '') {
                var adapter = new pimcore.plugin.definition[sourceDefinitionData.type](
                    sourceDefinitionData, key, this.getDeleteControl(
                        t("custom_report_adapter_"
                            + sourceDefinitionData.type), key),
                    this.getColumnSettings.bind(this));

                this.currentElements.push({
                    key: key,
                    adapter: adapter
                });
                this.currentElementCount++;

                this.sourceDefinitionsItems.add(adapter.getElement());
            }
            this.sourceDefinitionsItems.insert(0, this.getAddControl());
            this.sourceDefinitionsItems.updateLayout();
        },

        // get column configuration
        getColumnSettings: function () {
            var succField = this.sourceDefinitionFieldset.getComponent("totalRec");
            var errorField = this.sourceDefinitionFieldset.getComponent("errorMessage");
            var m = this.getValues();
            this.data.bridgeType = $("input[name='bridgename']").val();
            config = m.dataSourceConfig;
            if (config[0] != undefined && config[0].sql != '' && config[0].from != '') {
                Ext.Ajax.request({
                    url: "/DataConnect/connection/get-data-column",
                    method: "post",
                    params: {
                        configuration: Ext.encode(config),
                        name: this.data.name,
                        bridgeType: this.data.bridgeType
                    },
                    success: function (response) {
                        var res = Ext.decode(response.responseText);
                        errorField.setValue("");
                        if (res.success) {
                            this.updateColumnSettings(res.columns);
                        } else {
                            errorField.setValue(res.errorMessage);
                            this.columnGrid.getStore().removeAll();
                        }

                    }.bind(this)
                });
            }
        },

        // update column configuration
        updateColumnSettings: function (columns) {
            var insertData, isInStore, o;
            var cc = this.data.columnConfiguration;
            if (columns && columns.length > 0) {
                // cleanup
                this.columnStore.each(function (columns, rec) {
                    if (rec && !in_array(rec.get("name"), columns)) {
                        this.columnStore.remove(rec);
                    }
                }.bind(this, columns));
                // insert
                for (var i = 0; i < columns.length; i++) {
                    isInStore = (this.columnStore.findExact("name",
                        columns[i]) >= 0) ? true : false;
                    if (!isInStore) {
                        insertData = {
                            name: columns[i],
                            display: true,
                            label: ""
                        };
                        if (cc != null && typeof cc == "object" && cc.length > 0) {
                            for (o = 0; o < cc.length; o++) {
                                if (cc[o]["name"] == columns[i]) {
                                    insertData["display"] = (cc[o]["display"] == 0) ? false
                                        : true;
                                    insertData['label'] = cc[o]["label"];
                                    break;
                                }
                            }
                        }
                        this.columnStore.add(insertData);
                    }
                }
            }
        },

        // get all values
        getValues: function () {
            var allValues = this.generalDefinitionForm.getForm()
                .getFieldValues();

            var columnData = [];
            this.columnStore.each(function (rec) {
                columnData.push(rec.data);
            }.bind(this));
            allValues["columnConfiguration"] = columnData;
            var dataSourceConfig = [];
            for (var i = 0; i < this.currentElements.length; i++) {
                if (!this.currentElements[i].deleted) {
                    dataSourceConfig.push(this.currentElements[i].adapter
                        .getValues());
                }
            }
            allValues["dataSourceConfig"] = dataSourceConfig;
            allValues["sql"] = "";
            return allValues;
        },

        // save or update
        save: function (myVar) {
            var m = this.getValues();
            var that = this;
            var columns = [];
            for (i = 0; i < m.columnConfiguration.length; i++) {
                if (m.columnConfiguration[i].display == true) {
                    columns.push(m.columnConfiguration[i].display);
                }
            }
            if (this.generalDefinitionForm.getForm().isValid()) {
                if (columns.length > 0) {
                    if (m.active) {
                        Ext.Ajax.request({
                            url: "/DataConnect/connection/check-activate-mapping",
                            method: "post",
                            params: {
                                configuration: Ext.encode(m),
                                name: that.data.name,
                            },
                            async: false,
                            success: function (response) {
                                var res = Ext.decode(response.responseText);
                                if (res.success) {
                                    Ext.Msg.show({
                                        title: t('confirmation'),
                                        msg: t('activate_job_msg'),
                                        width: 400,
                                        height: 200,
                                        closable: false,
                                        buttonText:
                                            {
                                                yes: t('pure_active'),
                                                no: t('not_active'),
                                            },
                                        multiline: false,
                                        fn: function (buttonValue, inputText, showConfig) {
                                            if (buttonValue == 'yes') {
                                                Ext.Ajax.request({
                                                    url: "/DataConnect/connection/activate-mapping",
                                                    method: "post",
                                                    params: {
                                                        configuration: Ext.encode(m),
                                                        name: that.data.name,
                                                        active: '1'
                                                    },
                                                    async: false,
                                                    success: function (response) {
                                                        var res = Ext.decode(response.responseText);
                                                        if (res.success) {
                                                            pimcore.helpers.showNotification(t('success'),
                                                                t('saved_success'),
                                                                'success');


                                                        } else {
                                                            pimcore.helpers
                                                                .showNotification(
                                                                    t('error'),
                                                                    t('error_saving_msg'),
                                                                    'error');
                                                        }
                                                    }.bind(this)
                                                });
                                            } else if (buttonValue == 'no') {
                                                Ext.getCmp('activeCombo' + that.data.name).setValue(false);
                                                Ext.Ajax.request({
                                                    url: "/DataConnect/connection/activate-mapping",
                                                    method: "post",
                                                    params: {
                                                        configuration: Ext.encode(m),
                                                        name: that.data.name,
                                                        active: '0'
                                                    },
                                                    async: false,
                                                    success: function (response) {
                                                        var res = Ext.decode(response.responseText);
                                                        if (res.success) {
                                                            pimcore.helpers.showNotification(t('success'),
                                                                t('saved_success'),
                                                                'success');


                                                        } else {
                                                            pimcore.helpers
                                                                .showNotification(
                                                                    t('error'),
                                                                    t('error_saving_msg'),
                                                                    'error');
                                                        }
                                                    }.bind(this)
                                                });
                                            }

                                        },
                                        icon: Ext.Msg.QUESTION
                                    });

                                } else {
                                    Ext.Ajax.request({
                                        url: "/DataConnect/connection/activate-mapping",
                                        method: "post",
                                        params: {
                                            configuration: Ext.encode(m),
                                            name: that.data.name,
                                            active: '1'
                                        },
                                        async: false,
                                        success: function (response) {
                                            var res = Ext.decode(response.responseText);
                                            if (res.success) {
                                                pimcore.helpers.showNotification(t('success'),
                                                    t('saved_success'),
                                                    'success');


                                            } else {
                                                pimcore.helpers
                                                    .showNotification(
                                                        t('error'),
                                                        t('error_saving_msg'),
                                                        'error');
                                            }
                                        }.bind(this)
                                    });
                                }
                            }.bind(this)
                        });

                    } else {
                        Ext.Ajax.request({
                            url: "/DataConnect/connection/activate-mapping",
                            method: "post",
                            params: {
                                configuration: Ext.encode(m),
                                name: that.data.name,
                                active: '0'
                            },
                            async: false,
                            success: function (response) {
                                var res = Ext.decode(response.responseText);
                                if (res.success) {
                                    pimcore.helpers.showNotification(t('success'),
                                        t('saved_success'),
                                        'success');


                                } else {
                                    pimcore.helpers
                                        .showNotification(
                                            t('error'),
                                            t('error_saving_msg'),
                                            'error');
                                }
                            }.bind(this)
                        });
                    }

                    Ext.Ajax.request({
                        url: "/DataConnect/connection/update",
                        method: "post",
                        params: {
                            configuration: Ext.encode(m),
                            name: this.data.name
                        },
                        success: function (response) {
                            var res = Ext.decode(response.responseText);
                            if (res.success) {
                                pimcore.helpers.showNotification(t('success'),
                                    t('saved_success'),
                                    'success');
                                if (myVar == 'saveNext') {
                                    this.getFileInfo();
                                }

                            } else {
                                pimcore.helpers
                                    .showNotification(
                                        t('error'),
                                        t('error_saving_msg'),
                                        'error');
                            }
                        }.bind(this)
                    });
                } else {
                    pimcore.helpers.showNotification(t('error'), t('coloumn_msg_error'), 'error');
                }
            } else {
                Ext.Msg.alert(t('error'), t('required_msg'));
            }

            var scope = this;
            Ext.Ajax.request({
                url: "/DataConnect/connection/check-mapping",
                method: "post",
                params: {
                    name: this.data.name
                },
                success: function (response) {
                    var res = Ext.decode(response.responseText);
                    if (res.success) {
                        $("#panel2" + this.data.name + "_header").on('click', function (k, v) {
                            scope.getFileInfo();
                        });

                        $("#panel3" + this.data.name + "_header").on('click', function (k, v) {
                            scope.getBrickMappingAttributes();
                        });
                        $("#panel4" + this.data.name + "_header").on('click', function (k, v) {
                            scope.getFieldCollectionAttributes();
                        });

                        $("#panel5" + this.data.name + "_header").on('click', function (k, v) {
                            scope.getDataForSettingForm();
                        });

                    }
                }.bind(this)
            });
        }
    });
