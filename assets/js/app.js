$(document).ready(function(){
     $('#sidebarCollapse').click(function(){
        $('.content').toggleClass('get-pual');
        $('#sidebarCollapse').toggleClass('toggle');
        $('.sidebar-brand').toggleClass('pull');
        $('.navbar,.footer').toggleClass('pull');
         $('.sidebar').toggleClass('active');
     });
     
     $(function() {  
        $(".sidebar,body").niceScroll();
        $(".dropdown-menu").niceScroll();
    });
 });
