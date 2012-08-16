<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">	
    <title>Sencha Application</title>
    
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="apple-touch-icon" href="apple-touch-icon.png" />
    
    <link rel="stylesheet" href="<?php bloginfo("template_url"); ?>/lib/touch/resources/css/wp_touch.css" type="text/css">
    <link rel="stylesheet" href="<?php bloginfo("template_url"); ?>/public/resources/css/application.css" type="text/css">

    <script type="text/javascript">
        var INDEX_URL       = '<?php bloginfo("url"); ?>/?index';
        var MAXPAGE_URL     = '<?php bloginfo("url"); ?>/?maxpage';
        var SINGLE_URL      = '<?php bloginfo("url"); ?>/?single';
        var COMMENT_URL     = '<?php bloginfo("url"); ?>/?comment';
        var ICOMMENT_URL    = '<?php bloginfo("url"); ?>/?insertcomment';
        var THEME_URL       = '<?php bloginfo("template_url"); ?>';
        
        var BLOG_TITLE      = '<?php echo get_bloginfo( 'name' ) ?>';
        var BLOG_DESC       = '<?php echo get_bloginfo( 'description' ); ?>';
        
        // comment attibute
        var VISITOR_IP      = '<?php echo $_SERVER["REMOTE_ADDR"]; ?>';
        var VISITOR_AGENT   = '<?php echo $_SERVER["HTTP_USER_AGENT"] ?>';
    </script>
</head>
<body>
    <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/lib/touch/sencha-touch-debug.js"></script>
<!--    <script type="text/javascript" src="lib/touch/pkgs/platform/mvc.js"></script>-->
    
    <div id="sencha-app">
        <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/app/routes.js"></script>
        <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/app/app.js"></script>
        
        <!-- Place your view files here -->
        <div id="sencha-views">
            <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/app/views/Viewport.js"></script>
            <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/app/views/Post/Index.js"></script>
            <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/app/views/Post/Single.js"></script>
            <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/app/views/Post/Comment.js"></script>
        </div>
        
        <!-- Place your model files here -->
        <div id="sencha-models">
            <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/app/models/Post.js"></script>
            <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/app/models/Comment.js"></script>
        </div>
        
        <!-- Place your controller files here -->
        <div id="sencha-controllers">
            <script type="text/javascript" src="<?php bloginfo("template_url"); ?>/app/controllers/Post.js"></script>
        </div>
    </div>
</body>
</html>