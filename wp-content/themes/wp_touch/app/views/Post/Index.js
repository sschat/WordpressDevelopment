wp_touch.views.Index = Ext.extend(Ext.Panel, {
    layout: 'fit',
    initComponent: function() {
        this.store = new Ext.data.Store({
            autoLoad: true,
            model: 'Post'
        });
        
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            style: 'font-size:11px;',
            title: BLOG_TITLE + ': ' + BLOG_DESC
            }, {
                xtype: 'toolbar',
                dock: 'bottom',
                ui: 'light',
                items:[{
                    iconMask: true,
                    iconCls: 'arrow_left',
                    itemId: 'prevButton'
                }, {
                    iconMask: true,
                    itemId: 'nextButton',
                    iconCls: 'arrow_right'
                }]
        }];
        
        this.list = new Ext.List({
            itemTpl: '<div class="post">' + 
                        '<h2>{title}</h2>' +
                        '<div class="meta">by {author} on {date}</div>' +
                        '<p>{content}</p>' +
                    '</div>',
            store: this.store
        });

        this.items = [this.list];
        
        wp_touch.views.Index.superclass.initComponent.apply(this, arguments);
    } 
});

Ext.reg('wp_touch-index', wp_touch.views.Index);