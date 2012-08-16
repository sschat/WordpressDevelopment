/**
 * This file sets application-wide settings and launches the application when everything has
 * been loaded onto the page. By default we just render the applications Viewport inside the
 * launch method (see app/views/Viewport.js).
 */ 
wp_touch = new Ext.Application({
    defaultUrl: 'Post/index',
    name: "wp_touch",
    launch: function() {
        this.viewport = new wp_touch.Viewport();
    }
});
