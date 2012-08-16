Ext.regModel("Comment", {
    fields: [
        {name: "id",         type: "integer"},
        {name: "post_id",    type: "string"},
        {name: "author",     type: "string"},
        {name: "author_url", type: "string"},
        {name: "date",       type: "string"},
        {name: "content",    type: "string"}
    ],
    
    proxy: {
        type: 'ajax',
        url: COMMENT_URL,
        reader: {
            root: 'comments',
            type: 'json'
        }
    }
});