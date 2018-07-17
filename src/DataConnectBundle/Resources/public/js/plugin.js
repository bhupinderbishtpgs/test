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
 * This is main startup configuration file for the plugin
 * 
 */

pimcore.registerNS("pimcore.plugin.dataconnect");

pimcore.plugin.dataconnect = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.dataconnect";
    },
    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },
    pimcoreReady: function (params, broker) {

        if (pimcore.globalmanager.get("user").isAllowed("plugin_DataConnect_access")) {
            var toolbar = new pimcore.plugin.toolbar();
            toolbar.leftNavigation();
        }

    },
//    postOpenAsset: function (asset, type) {
//        if (asset.data.type == 'folder' && (asset.data.filename == 'DataConnect' || asset.data.path == '/DataConnect/')) {
//
//            //remove save
//            asset.toolbarButtons.publish.hide();
//
//            //remove delete
//            asset.toolbarButtons.remove.hide();
//
//            //remove rename
//            asset.toolbarButtons.rename.hide();
//        }
//    },
//
//    prepareAssetTreeContextMenu: function (menu, treeClass, assetRecord) {
//
//        if (assetRecord.data.type == 'folder') {
//
//            var child = assetRecord.data.path;
//            child = child.split('/');
//
//            //a list of menu items allowed on restricted folder
//            var allowedFolderItems = ['Copy', 'Refresh', 'Add asset(s)', 'Advanced'];
//
//            //a list of folders where menu item and class object creation filters will be applied
//            var applyFolderFilters = ['DataConnect'];
//
//            if (child[2] != '_reverse: function') {
//                applyFolderFilters.push(child[2]);
//            }
//
//            menu.items.items.forEach(function (el) {
//                //remove menu items : those are not in allowedFolderItems array from the applyFolderFilters folder array
//                if (allowedFolderItems.indexOf(el.text) == '-1' && applyFolderFilters.indexOf(assetRecord.data.text) != '-1') {
//                    el.hide();
//                }
//            });
//        }
//
//    },

    /*execute : function(conn,status) {
     Ext.Ajax.request({
     url: "/plugin/DataConnect/connection/run",
     async:false,
     params: {name:conn,status:status},
     method: 'GET',
     success: function(resp) {
     
     var response = Ext.decode(resp.responseText);
     
     if (response.success) {
     
     pimcore.helpers.showNotification(t('success'),response.msg,'success');
     
     } else {
     
     Ext.MessageBox.alert(t('error'),response.msg);
     }
     }   
     });
     },
     // post open bridge class object
     postOpenObject : function(obj){
     
     var className = obj.data.general.o_className;
     if(className == "Bridge") {
     var objData = obj.data.data;
     // Button to check connection with data source
     obj.toolbar.insert(10, { //position of the button
     text: '',
     itemId: 'test_connection',
     scale: 'medium',
     iconCls : 'plugin_data_connect_icon_test',
     tooltip: {
     text: t('test_connection'),
     xtype: "quicktip"
     },
     handler: function() {
     
     Ext.Ajax.request({
     url: '/plugin/DataConnect/bridge/connect',
     async:false,
     params: {object_id: obj.id, DataSource:objData.DataSource, HostName:objData.HostName, Password:objData.Password, Port:objData.Port, Username:objData.Username, DatabaseName:objData.DatabaseName},
     method: 'GET',
     success: function(resp) {
     
     var response = Ext.decode(resp.responseText);
     Ext.Msg.alert(t('test_connection'), response.msg);
     }   
     });
     }
     });
     obj.toolbar.insert(11, { //position of the button
     text: '',
     itemId: 'run_bridge',
     scale: 'medium',
     iconCls : 'plugin_data_connect_icon_execute',
     tooltip: {
     text: t('execute'),
     xtype: "quicktip"
     },
     handler: function() {
     Ext.Msg.confirm(t('confirmation'), t('execute_msg'), function(confirm){
     var dataconnect = new pimcore.plugin.dataconnect();    
     if(confirm === "yes") {
     Ext.Ajax.request({
     url: "/plugin/DataConnect/connection/checkauth",
     params: {object_id: obj.id, name:objData.BridgeName},
     method: "GET",
     success: function(res) {
     
     var data = Ext.decode(res.responseText);
     
     if (data.success) {
     //Execution
     dataconnect.execute(data.conn,2);
     
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
     dataconnect.execute(data.conn,1);
     } else if(buttonValue=='no') {
     dataconnect.execute(data.conn,2);
     }
     
     },
     icon : Ext.Msg.QUESTION
     });
     
     } else {
     Ext.MessageBox.alert(t("error"),data.msg);
     }
     }
     
     
     
     }
     });
     
     
     }
     }, this);
     }
     });
     }	
     
     
     },*/
    // post save bridge class object
//    postSaveObject : function(obj) {
//    	
//    	var className = obj.data.general.o_className;
//    	if(className == "Bridge") {
//    		var objData = obj.data.data;
//        	var req = Ext.Ajax.request({
//                url: "/plugin/DataConnect/bridge/update-bridge",
//                async:true,
//                params: {object_id: obj.id, name:objData.BridgeName, dataSource:objData.DataSource, hostName:objData.HostName,userName:objData.Username, databaseName:objData.DatabaseName},
//                method: 'GET',
//                success: function(resp) {
//                	
//                	var response = Ext.decode(resp.responseText);
//                	if (response.success) {
//                	} else {
//                	}
//                }   
//            });
//        	
//        	
//        }
//    }
});

var dataconnectPlugin = new pimcore.plugin.dataconnect();