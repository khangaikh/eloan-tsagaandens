$(function(){
  
    //enable / disable
   $('#enable').click(function() {
       $('#user .editable').editable('toggleDisabled');
   });    
    
    //editables 
    
    $('#pay1 a').editable({
        name: '1',
        url: 'edit_graphic.php',
        display: function(value, response) {
        //render response into element
        var m = moment().format("YYYY-MM-DD");
        $(this).html(value);
        $(this).closest("p").text(m);
        //$(“input”).attr(“disabled”, true);
        }
    });

    $('#pay2 a').editable({
        name: '2',
        url: 'edit_graphic.php',
        display: function(value, response) {
        //render response into element

        $(this).html(value);
        //$(this).closest("td").next().text(re);
        //$(“input”).attr(“disabled”, true);
        }
    });

    $('#pay3 a').editable({
        name: '3',
        url: 'edit_graphic.php',
        display: function(value, response) {
        //render response into element
        $(this).html(value);
        //$(this).closest("td").next().text(re);
        //$(“input”).attr(“disabled”, true);
        }
    });

   
});