<?php



/*
 * Change the login header
 */
function my_custom_login_logo() {

    echo '<style type="text/css">
        body { background: #707070 url('. plugins_url( 'bg.png' , __FILE__) . ') top center no-repeat !important; }
        
        #wrapper { overflow:visible;
        float: left; left:50%; } 
        
        h1 a { display : none }
        .login h1 a {  background: url('. plugins_url( 'missing.png' , __FILE__) . ') no-repeat top center; }

    </style>
   
    ';
    
}
add_filter('login_head', 'my_custom_login_logo');