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
 */

pimcore.registerNS("pimcore.plugin.definition.csv");
pimcore.plugin.definition.csv = Class.create({

    element: null,
    sourceDefinitionData: null,
    columnSettingsCallback: null,

    initialize: function (sourceDefinitionData, key, deleteControl, columnSettingsCallback) {
        this.sourceDefinitionData = sourceDefinitionData;
        this.columnSettingsCallback = columnSettingsCallback;
        this.groupByStore = new Ext.data.ArrayStore({
            fields: ['text'],
            data: [],
            expandData: true
        });

        this.element = new Ext.form.FormPanel({
            key: key,
            bodyStyle: "padding:10px;",
            autoHeight: true,
            border: false,
            tbar: deleteControl,
            listeners: {
                afterrender: function () {
                    this.updateGroupByMultiSelectStore(true);
                }.bind(this)
            },
            items: [
                {
                    xtype: "textfield",
                    name: "delimiter",
                    hidden: true,
                    fieldLabel: 'DELIMITER <br /><small>(eg. ")</small>',
                    value: (sourceDefinitionData ? sourceDefinitionData.delimiter : ""),
                    width: 400,
                    //height: 20,
                    enableKeyEvents: true,
                    listeners: {
                        keyup: this.onSqlEditorKeyup.bind(this)
                    }
                },
                {
                    fieldLabel: t('csv_file_path_label'),
                    name: 'csvFilePath',
                    fieldCls: 'pimcore_droptarget_input',
                    value: (sourceDefinitionData ? sourceDefinitionData.csvFilePath : ""),
                    xtype: 'textfield',
                    enableKeyEvents: true,
                    tip: t('csv_file_path_tooltip'),
                    width: 400,
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

                                    if (data.elementType == 'asset') {
                                        return Ext.dd.DropZone.prototype.dropAllowed;
                                    }

                                    return Ext.dd.DropZone.prototype.dropNotAllowed;
                                },
                                onNodeDrop: function (target, dd, e, data) {
                                    data = data.records[0].data;
                                    pos = data.path.lastIndexOf(".");
                                    var extenstion = data.path.slice(pos + 1);
                                    console.log(data);
                                    if (extenstion.toLowerCase() == "csv" || extenstion.toLowerCase() == "xls" || extenstion.toLowerCase() == "xlsx") {
                                        if (data.elementType == 'asset') {
                                            this.setValue(data.path);
                                            return true;
                                        }
                                    } else {
                                        Ext.MessageBox.alert('Not Allowed', 'Only csv or excel file is allowed');
                                    }


                                    return false;
                                    if (data.elementType == 'asset') {

                                        this.setValue(data.path);

                                        return true;

                                    }

                                    return false;
                                }.bind(el)
                            });
                        },
                        keyup: function (el) {
                            el.setValue('');
                        },
                        change: this.onSqlEditorKeyup.bind(this)
                    }
                }

            ]
        });

        this.element.updateLayout();
    },

    getElement: function () {
        return this.element;
    },

    getValues: function () {
        var values = this.element.getForm().getFieldValues();
        values.type = "csv";
        return values;
    },

    onSqlEditorKeyup: function () {
        clearTimeout(this._keyupTimout);

        var self = this;
        this._keyupTimout = setTimeout(function () {
            self.updateGroupByMultiSelectStore(false);
        }, 500);
    },

    updateGroupByMultiSelectStore: function (addItem) {
        this.columnSettingsCallback();
        var values = this.getValues();

        if (this.sqlText) {
            var sqlText = "";
            if (values.sql) {
                if (values.sql.indexOf("SELECT") < 0 || values.sql.indexOf("SELECT") > 5) {
                    sqlText += "SELECT ";
                }
                sqlText += values.sql;
            }

            if (values.from) {
                if (values.from.indexOf("FROM") < 0) {
                    sqlText += " FROM ";
                }
                sqlText += values.from;
            }

            if (values.where) {
                if (values.where.indexOf("WHERE") < 0) {
                    sqlText += " WHERE ";
                }
                sqlText += values.where;
            }

            if (values.groupby) {
                if (values.groupby.indexOf("GROUP BY") < 0) {
                    sqlText += " GROUP BY ";
                }
                sqlText += values.groupby;
            }

            this.sqlText.setValue(sqlText);
        }
    }
});
