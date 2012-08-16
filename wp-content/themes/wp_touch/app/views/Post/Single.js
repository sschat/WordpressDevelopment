wp_touch.views.Single = Ext.extend(Ext.Panel, {
    layout: 'fit',
    initComponent: function() {
        this.formComment = new Ext.form.FormPanel({
            url: ICOMMENT_URL,
            floating: true,
            centered: true,
            modal: true,
            items: [{
                xtype: 'hiddenfield',
                name: 'author_agent',
                value: VISITOR_AGENT
            }, {
                xtype: 'hiddenfield',
                name: 'author_ip',
                value: VISITOR_IP
            }, {
                xtype: 'textfield',
                name: 'author',
                label: 'Name'
            }, {
                xtype: 'emailfield',
                name: 'email',
                label: 'Email',
                useClearIcon: true
            }, {
                xtype: 'urlfield',
                name: 'url',
                label: 'Website',
                useClearIcon: true
            }, {
                xtype: 'textareafield',
                name: 'content',
                label: 'Comment'
            }],
            dockedItems: [{
                    xtype: 'toolbar',
                    title: 'Add Your Comment'
            }, {
                xtype: 'toolbar',
                dock: 'bottom',
                ui: 'light',
                items: [{xtype: 'spacer'}, {
                    text: 'Reset',
                    itemId: 'cResetButton'
                }, {
                    text: 'Save',
                    ui: 'confirm',
                    itemId: 'cSaveButton'
                }]}
            ]
        });
        
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            title: '',
            itemId: 'singlePostToolbar',
            style: 'font-size:11px'
        }, {
            xtype: 'toolbar',
            ui: 'light',
            dock: 'bottom',
            items: [{
                itemId: 'homeButton',
                ui: 'back',
                text: 'Back'
            }, { xtype: 'spacer' }, {
                iconCls: 'chat',
                iconMask: true,
                itemId: 'commentButton'
            }, {
                iconCls: 'chat1',
                itemId: 'addCommentButton',
                iconMask: true
            }] 
        }];
    
        this.store = new Ext.data.Store({
            autoLoad: true,
            model: 'Post'
        });
    
        this.xtpl = new Ext.XTemplate(
            '<div class="post single">',
            '<tpl for=".">',
                '<h2>{title}</h2>',
                '<div class="meta">by {author} on {date}</div>',
                '<p><p>{content}</p></p>',       
            '</tpl>',
            '</div>'
        );
    
        this.dataView = new Ext.DataView({
            store: this.store,            
            tpl: this.xtpl,
            itemSelector: 'div.node'
        });
        
        this.items = [this.dataView];
    
        wp_touch.views.Single.superclass.initComponent.apply(this, arguments);
    }
});

Ext.reg('wp_touch-single', wp_touch.views.Single);