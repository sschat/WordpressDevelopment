wp_touch.views.Comment = Ext.extend(Ext.Panel, {
    layout: 'fit',
    initComponent: function() {        
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            title: '',
            itemId: 'commentPostToolbar',
            style: 'font-size:11px'
        }, {
            xtype: 'toolbar',
            ui: 'light',
            dock: 'bottom',
            items: [{
                itemId: 'backButton',
                ui: 'back',
                text: 'Back'
            }, { xtype: 'spacer' }, {
                itemId: 'homeButton',
                iconMask: true,
                iconCls: 'home'
            }]
        }];
    
        this.storeComment = new Ext.data.Store({
            autoLoad: true,
            model: 'Comment'
        });
        
        this.xtplComment = new Ext.XTemplate(
            '<tpl for=".">',
            '<div class="comments">',
                '<div class="cmeta"><i><b>{author}</b></i> on <b>{date}</b></div>',
                '<p class="ccontent">{content}</p>',
            '</div>',
            '</tpl>'
        );
        
        this.dataViewComment = new Ext.DataView({
            store: this.storeComment,
            tpl: this.xtplComment,
            itemSelector: 'div.node'
        });
        
        this.items = [this.dataViewComment];
    
        wp_touch.views.Comment.superclass.initComponent.apply(this, arguments);
    }
});

Ext.reg('wp_touch-comment', wp_touch.views.Comment);