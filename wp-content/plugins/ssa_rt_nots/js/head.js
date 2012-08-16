// Enable pusher logging - don't include this in production
Pusher.log = function(message) {
  if (window.console && window.console.log) window.console.log(message);
};

// Flash fallback logging - don't include this in production
WEB_SOCKET_DEBUG = true;

$(document).ready(function(){
// add element for showing the  messages
$("body").append("<div class='ssa_rt_not'><ul></ul></div>");


});

var count = 0;
var pusher = new Pusher('0435991fa523b3b3f04e');
var channel = pusher.subscribe('test_channel');
channel.bind('my_event', function(data) {

    // Add the recieved notification to the list
    // remove it after x seconds
    count++;
    $('.ssa_rt_not ul').append('<li id=' + count + '>' + data + "</li>");
    $('.ssa_rt_not li#' + count).slideDown();


   
    
});


