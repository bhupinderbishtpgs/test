pimcore.registerNS("pimcore.plugin.NotificationBundle");

pimcore.plugin.NotificationBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.NotificationBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("NotificationBundle ready!");
    }
});

var NotificationBundlePlugin = new pimcore.plugin.NotificationBundle();
