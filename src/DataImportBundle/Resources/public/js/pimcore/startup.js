pimcore.registerNS("pimcore.plugin.DataImportBundle");

pimcore.plugin.DataImportBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.DataImportBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        Ext.override(pimcore.asset.tree, {
            uploadFileList: function (dataTransfer, parentNode) {
                var vendorFolder = 'Vendor';
                var file;
                this.activeUploads = 0;


                var win = new Ext.Window({
                    items: [],
                    modal: true,
                    closable: false,
                    bodyStyle: "padding:10px;",
                    width: 500,
                    autoHeight: true,
                    autoScroll: true
                });
                win.show();

                var doFileUpload = function (file, path) {

                    if(typeof path == "undefined") {
                        path = "";
                    }

                    this.activeUploads++;

                    var pbar = new Ext.ProgressBar({
                        width:465,
                        text: file.name,
                        style: "margin-bottom: 5px"
                    });

                    win.add(pbar);
                    win.updateLayout();

                    var finishedErrorHandler = function (e) {
                        this.activeUploads--;
                        win.remove(pbar);

                        if(this.activeUploads < 1) {
                            win.close();
                            pimcore.elementservice.refreshNodeAllTrees("asset", parentNode.get("id"));
                        }
                        
                        if(e && parentNode.data.basePath.indexOf(vendorFolder) !== -1) {
                            
                            var successWindow = new Ext.Window({
                                modal: true,
                                iconCls: "pimcore_icon_success",
                                title: t("Success"),
                                width: 700,
                                maxHeight: 500,
                                html: t("Your file has been successfully uploaded and a corresponding job has been added"),
                                autoScroll: true,
                                bodyStyle: "padding: 10px; background:#fff;",
                                buttonAlign: "center",
                                shadow: false,
                                closable: false,
                                buttons: [{
                                    text: t("OK"),
                                    handler: function () {
                                        successWindow.close();
                                    }
                                }]
                            });
                            successWindow.show();
                        }
                        
                    }.bind(this);

                    var errorHandler = function (e) {
                        var error = Ext.JSON.decode(e["responseText"]);
                        pimcore.helpers.showNotification(t("error"), error.message, "error");
                        finishedErrorHandler();
                    }.bind(this);

                    pimcore.helpers.uploadAssetFromFileObject(file,
                        "/admin/asset/add-asset?parentId=" + parentNode.id + "&dir=" + path,
                        finishedErrorHandler,
                        function (evt) {
                            //progress
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                var progressText = file.name + " ( " + Math.floor(percentComplete*100) + "% )";
                                if(percentComplete == 1) {
                                    progressText = file.name + " " + t("converting") + "... ";
                                }

                                pbar.updateProgress(percentComplete, progressText);
                            }
                        },
                        errorHandler
                    );
                }.bind(this);

                if(dataTransfer["items"] && dataTransfer.items[0] && dataTransfer.items[0].webkitGetAsEntry) {
                    // chrome
                    var traverseFileTree = function (item, path) {
                        path = path || "";
                        if (item.isFile) {
                            // Get file
                            item.file(function (file) {
                                doFileUpload(file, path);
                            }.bind(this));
                        } else if (item.isDirectory) {
                            // Get folder contents
                            var dirReader = item.createReader();
                            dirReader.readEntries(function (entries) {
                                for (var i = 0; i < entries.length; i++) {
                                    traverseFileTree(entries[i], path + item.name + "/");
                                }
                            });
                        }
                    }.bind(this);

                    for (var i = 0; i < dataTransfer.items.length; i++) {
                        // webkitGetAsEntry is where the magic happens
                        var item = dataTransfer.items[i].webkitGetAsEntry();
                        if (item) {
                            traverseFileTree(item);
                        }
                    }
                } else if(dataTransfer["files"]) {
                    // default filelist upload
                    for (var i=0; i<dataTransfer["files"].length; i++) {
                        file = dataTransfer["files"][i];

                        if (window.FileList && file.name && file.size) { // check for size (folder has size=0)
                            doFileUpload(file);
                        }
                    }

                    // if no files are uploaded (doesn't match criteria, ...) close the progress win immediately
                    if(!this.activeUploads) {
                        win.close();
                    }
                }

                // check in 5 sec. if there're active uploads
                // if not, close the progressbar
                // this is necessary since the folder upload is async, so we don't know if the progress is
                // necessary or not, not really perfect solution, but works as it should
                window.setTimeout(function () {
                    if(!this.activeUploads) {
                        win.close();
                    }
                }.bind(this), 5000);
            }
        });
    },
    
        prepareAssetTreeContextMenu: function (menu, treeClass, assetRecord) {

        var vendorFolder = 'Vendor';
        var allowedSubmenuItems = ['Upload Files'];
        
        if (assetRecord.data.type == 'folder') {

            var fullPath = assetRecord.data.path;
            if(fullPath.indexOf(vendorFolder) != -1 ){                
                menu.items.each(function(item){
                    if(item.text == "Add asset(s)"){
                        item.menu.items.each(function(submenuItem){
                            if( allowedSubmenuItems.indexOf(submenuItem.text) == -1){
                                submenuItem.setHidden(true);
                            }
                        });
                    }
                })                
            }
        }

    },
});

var DataImportBundlePlugin = new pimcore.plugin.DataImportBundle();
