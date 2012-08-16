Ext.regModel("Post", {
    fields: [
        {name: "id", type: "integer"},
        {name: "title", type: "string"},
        {name: "permalink", type: "string"},
        {name: "date", type: "string"},
        {name: "author_id", type: "string"},
        {name: "author", type: "string"},
        {name: "content", type: "string"},
    ],
    
    proxy: {
        type: 'ajax',
        url: INDEX_URL,
        reader: {
            root: 'posts',
            type: 'json'
        }
    }
});