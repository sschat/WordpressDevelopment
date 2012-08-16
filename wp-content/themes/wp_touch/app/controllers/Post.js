Ext.regController("Post", {
    // index controller: main page with pagination
    index: function(page) {
        
        if (typeof(page) == 'object') {
            page = 1;
        }
        
        if (!this.listPanel) {
            this.listPanel = this.render({
                xtype: 'wp_touch-index',
                listeners: {
                    list: {
                        select: function(list, record) {
                            this.single(record.data.id);
                        },
                        scope: this
                    },
                    activate : function(listPanel) {
                        listPanel.list.getSelectionModel().deselectAll();
                    }
                }
            });
            
            this.listPanel.store.proxy.url = INDEX_URL + '&page=' + page;
            
            var added = false;
            
            this.listPanel.query('#prevButton')[0].on({
                tap: function() {
                    if (added) {
                        page++;
                    }
                    page--;
                    if (page > 0) {
                        this.listPanel.store.proxy.url = INDEX_URL + '&page=' + page;
                        this.listPanel.store.load();
                    } else {
                        page++;
                    }
                    added = false;
                },
                scope: this
            });
            
            this.listPanel.query('#nextButton')[0].on({
                tap: function() {
                    page++;
                    this.listPanel.store.proxy.url = INDEX_URL + '&page=' + page;
                    this.listPanel.store.load();
                    Ext.Ajax.request({
                        url: MAXPAGE_URL,
                        method: 'POST',
                        success: function(xhr) {
                            if (page >= parseInt(xhr.responseText)) {
                                page = parseInt(xhr.responseText) - 1;
                                added = true;
                            }
                        }
                    });
                },
                scope: this
            });
            
            wp_touch.viewport.setActiveItem(this.listPanel);
        } else {
            wp_touch.viewport.setActiveItem(this.listPanel, {
                type: 'slide',
                direction: 'right'
            });
        }
    },
    
    // single post
    single: function(post_id) {
        var singlePost = this.render({
            xtype: 'wp_touch-single'
        });
        singlePost.dataView.store.proxy.url = SINGLE_URL + '&id=' + post_id;
        
        // back to index
        singlePost.query('#homeButton')[0].on({
            tap: function() {
                this.index(1);
            },
            scope: this
        });
        
        // move to comment list
        singlePost.query('#commentButton')[0].on({
            tap: function() {
                var post = singlePost.dataView.store.data.items[0].data;                
                this.comment(post.id, post.title);
            },
            scope: this
        });
        
        // show comment form
        singlePost.query('#addCommentButton')[0].on({
           tap: function() {
               singlePost.formComment.show();
           },
           scope: this
        });
        
        // submit a comment
        singlePost.formComment.query('#cSaveButton')[0].on({
           tap: function() {
               singlePost.formComment.submit({
                  params: {
                      post_id: post_id
                  },
                  success: function(e) {
                      singlePost.formComment.reset();
                      singlePost.formComment.hide();
                  } 
               });
           },
           scope: this
        });
        
        // reset comment form
        singlePost.formComment.query('#cResetButton')[0].on({
           tap: function() {
               singlePost.formComment.reset();
           } 
        });
        
        singlePost.dataView.store.addListener('datachanged', function() {
            var title = singlePost.dataView.store.data.items[0].data.title
            singlePost.query('#singlePostToolbar')[0].setTitle(
                BLOG_TITLE + ': ' + title
            );
        });
            
        wp_touch.viewport.setActiveItem(singlePost, {
            type: 'slide'
        });
    },
    
    // comment page for each post
    comment: function(post_id, title) {
        var commentPanel = this.render({
            xtype: 'wp_touch-comment'
        });
        
        commentPanel.dataViewComment.store.proxy.url = COMMENT_URL + '&post_id=' + post_id;
        
        commentPanel.query('#commentPostToolbar')[0].setTitle('Comments On: ' + title);
        commentPanel.query('#backButton')[0].on({
            tap: function() {
                this.single(post_id);
            },
            scope: this
        });
        
        commentPanel.query('#homeButton')[0].on({
            tap: function() {
                this.index(1);
            },
            scope: this
        });
            
        wp_touch.viewport.setActiveItem(commentPanel, {
            type: 'slide'
        });
    }
});
