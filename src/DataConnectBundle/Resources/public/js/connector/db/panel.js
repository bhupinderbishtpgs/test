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
 *
 * This file is used for data connector list panel
 *
 */

pimcore.registerNS("pimcore.plugin.db.panel");
pimcore.plugin.db.panel = Class
    .create({
        initialize: function () {
        },
        // data connector tab panel
        getTabPanel: function () {
            if (!this.panel) {
                this.panel = new Ext.Panel({
                    border: false,
                    layout: "border",
                    items: [this.getTree(), this.getEditPanel()]
                });

                pimcore.layout.refresh();
            }

            return this.panel;
        },
        // get data connections tree listing
        getTree: function () {
            if (!this.tree) {
                var store = Ext.create('Ext.data.TreeStore', {
                    autoLoad: false,
                    autoSync: true,
                    proxy: {
                        type: 'ajax',
                        url: '/DataConnect/connection/tree',
                        reader: {
                            type: 'json'
                        }
                    },
                    root: {
                        iconCls: "pimcore_icon_thumbnails"
                    }
                });

                this.tree = new Ext.tree.TreePanel({
                    id: "data_connector_tree",
                    store: store,
                    region: "west",
                    autoScroll: true,
                    animate: false,
                    containerScroll: true,
                    width: 250,
                    split: true,
                    root: {
                        id: '0',
                        expanded: true
                    },
                    rootVisible: false,
                    listeners: this.getTreeNodeListeners(),
                    tbar: {
                        items: [{
                            text: 'Add Import Mapping',
                            iconCls: "data_connector_icon",
                            handler: this.addField.bind(this)
                        }]
                    }
                });

                this.tree.on("render", function () {
                    this.getRootNode().expand();
                });
            }

            return this.tree;
        },
        // get connection configuration edit panel


        getEditPanel: function () {

            var homeButton = new Ext.Button({
                text: 'Home',
                iconCls: "pimcore_home_icon ",
                scale: "medium",
//                    style: {
//                        'margin-right': '10px'
//                    },
                handler: function () {
                    var customerViewPanelId = 'custom_dashboard';
                    try {
                        pimcore.globalmanager.get(customerViewPanelId).activate();
                    } catch (e) {
                        console.log(e);
                    }
                }
            });


            var logoutButton = new Ext.Button({
                text: 'Logout',
                iconCls: "pimcore_logout_icon",
                scale: "medium",
//                    style: {
//                        'margin-right': '10px'
//                    },
                handler: function () {
                    Ext.Ajax.request({
                        url: '/get-host-url',
                        success: function (response) {
                            response = Ext.decode(response.responseText);
                            if (response.success) {
                                var hostUrl = response.hostUrl;
                                var redirectTo = hostUrl + "/admin/logout";
                                window.location = redirectTo;
                            }


                        }.bind(this)
                    });
                }
            })

            if (!this.editPanel) {
                this.editPanel = new Ext.TabPanel({
                    region: "center",
                    tbar: {
                        items: [homeButton, logoutButton]
                    },
                    plugins: ['tabclosemenu']
                });
            }

            return this.editPanel;
        },
        // connections event listeners
        getTreeNodeListeners: function () {
            var treeNodeListeners = {
                'itemclick': this.onTreeNodeClick.bind(this),
                "itemcontextmenu": this.onTreeNodeContextmenu.bind(this),
                'beforeitemappend': function (thisNode, newChildNode,
                                              index, eOpts) {
                    newChildNode.data.leaf = true;
                    newChildNode.data.expaned = true;
                    newChildNode.data.iconCls = "pimcore_icon_sql"
                }
            };

            return treeNodeListeners;
        },
        onTreeNodeClick: function (tree, record, item, index, e, eOpts) {
            this.openConfig(record.data.id);
        },
        // opens data connection configuration
        openConfig: function (id) {
            var existingPanel = Ext.getCmp("pimcore_sql_panel_" + id);

            if (existingPanel) {
                this.editPanel.setActiveTab(existingPanel);
                return;
            }

            Ext.Ajax
                .request({
                    url: "/DataConnect/connection/get-connection-details",
                    params: {
                        name: id
                    },
                    success: function (response) {
                        var data = Ext.decode(response.responseText);

                        if (data.success) {
                            var fieldPanel = new pimcore.plugin.db.item(
                                data, id, this);
                        } else {
                            pimcore.helpers.showNotification('Error',
                                'Error while getting info.',
                                'error');
                            this.tree.getStore().load({
                                node: this.tree.getRootNode()
                            });
                        }
                        pimcore.layout.refresh();
                    }.bind(this)
                });
        },
        onTreeNodeContextmenu: function (tree, record, item, index, e,
                                         eOpts) {
            e.stopEvent();

            tree.select();

            var menu = new Ext.menu.Menu();
            menu.add(new Ext.menu.Item({
                text: t('delete'),
                iconCls: "pimcore_icon_delete",
                handler: this.deleteField.bind(this, tree, record)
            }));
            menu.add(new Ext.menu.Item({
                text: "Duplicate",
                iconCls: "pimcore_icon_copy",
                handler: this.duplicate.bind(this, tree, record)
            }));
            menu.add(new Ext.menu.Item({
                text: "Rename",
                iconCls: "pimcore_icon_key",
                handler: this.renameMapping.bind(this, tree, record)
            }));
            menu.showAt(e.pageX, e.pageY);
        },
        // add connection button
        addField: function () {
            Ext.MessageBox.prompt('Add Data Import Mapping',
                'Enter the name of new data import mapping(a-zA-Z-_)',
                this.addFieldComplete.bind(this), null, null, "");
        },
        // add connection
        addFieldComplete: function (button, value, object) {

            var regresult = value.match(/[a-zA-Z0-9_\- ]+/);
            if (button == "ok" && value.length > 2 && regresult == value) {
                value = value.replace(" ", "-");
                var codes = this.tree.getRootNode().childNodes;
                for (var i = 0; i < codes.length; i++) {
                    if (codes[i].text == value) {
                        Ext.MessageBox.alert(
                            t('add_conector_title'),
                            t('connector_key_exist'));
                        return;
                    }
                }


                Ext.Ajax
                    .request({
                        url: "/DataConnect/connection/add",
                        params: {
                            name: value
                        },
                        success: function (response) {
                            var data = Ext.decode(response.responseText);
                            this.tree.getStore().load({
                                node: this.tree.getRootNode()
                            });

                            if (!data || !data.success) {
                                Ext.Msg.alert(t('error'), t('connector_add_error'));
                            } else if (data.conn == false) {
                                Ext.Msg.alert(t('error'), "Connection already Exists");
                            } else {
                                this.openConfig(data.id);
                            }
                        }.bind(this)
                    });


            } else if (button == "cancel") {
                return;
            } else {
                Ext.Msg.alert(t('error'),
                    t('connector_add_error'));
            }
        },
        // delete connection
        deleteField: function (tree, record) {
            Ext.Ajax.request({
                url: "/DataConnect/connection/delete",
                params: {
                    name: record.data.id
                }
            });

            var activeTabIndex = this.editPanel.items.findIndex('id', "pimcore_sql_panel_" + record.data.id);
            this.editPanel.remove(this.editPanel.items.getAt(activeTabIndex));
            record.remove();
        },
        // duplicate function
        duplicate: function (tree, record) {
            Ext.Ajax.request({
                url: "/DataConnect/connection/duplicate",
                params: {
                    panelId: record.data.id
                },
                success: function (response) {
                    var data = Ext.decode(response.responseText);
                    if (data.success) {
                        this.tree.getStore().load({
                            node: this.tree.getRootNode()
                        });
                        this.openConfig(data.id);
                    } else if (data.success == false) {
                        Ext.MessageBox.alert('Error', data.errorMessage);
                    } else {
                        pimcore.helpers.showNotification(t('error'), t('error_info_msg'), 'error');
                    }
                }.bind(this)
            });
        },
        // rename connection button
        renameMapping: function (tree, record) {
            Ext.MessageBox.prompt('Rename Import Mapping',
                'Please enter the new name(a-zA-Z-_)',
                this.renameMappingComplete.bind(this, record), null, null, record.data.id);
        },
        // rename connection
        renameMappingComplete: function (record, button, value, object) {
            var regresult = value.match(/[a-zA-Z0-9_\- ]+/);
            if (button == "ok" && value.length > 2 && regresult == value) {
                value = value.replace(" ", "-");
                var codes = this.tree.getRootNode().childNodes;
                for (var i = 0; i < codes.length; i++) {
                    if (codes[i].text == value) {
                        Ext.MessageBox.alert(
                            t('add_conector_title'),
                            t('connector_key_exist'));
                        return;
                    }
                }

                Ext.Ajax
                    .request({
                        url: "/DataConnect/connection/rename",
                        params: {
                            oldName: record.data.id,
                            updatedName: value
                        },
                        success: function (response) {
                            var data = Ext.decode(response.responseText);
                            this.tree.getStore().load({
                                node: this.tree.getRootNode()
                            });

                            if (!data || !data.success) {
                                Ext.Msg.alert(t('error'), t('connector_add_error'));
                            } else if (data.conn == false) {
                                Ext.Msg.alert(t('error'), "Name already Exists");
                            } else {
                                this.openConfig(data.id);
                            }
                        }.bind(this)
                    });
            } else if (button == "cancel") {
                return;
            } else {
                Ext.Msg.alert(t('error'),
                    t('connector_add_error'));
            }
        }
    });

