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
    $('#loss a').editable({
        name: '2',
        url: 'edit_graphic.php',
        display: function(value, response) {
        //render response into element

        $(this).html(value);
        //$(this).closest("td").next().text(re);
        //$(“input”).attr(“disabled”, true);
        }
    });

    $('#rate a').editable({
        name: '3',
        url: 'edit_graphic.php',
        display: function(value, response) {
        //render response into element
        $(this).html(value);
        //$(this).closest("td").next().text(re);
        //$(“input”).attr(“disabled”, true);
        }
    });

    $('#due_pay a').editable({
        name: '4',
        url: 'edit_graphic.php',
        display: function(value, response) {
        //render response into element
        $(this).html(value);
        //$(this).closest("td").next().text(re);
        //$(“input”).attr(“disabled”, true);
        }
    });
    $('#vacation a').editable({
        name: '5',
        url: 'edit_graphic.php',
        display: function(value, response) {
            //render response into element
            var m = moment().format("YYYY-MM-DD");
            $(this).html(value);
            $(this).closest("p").text(value);
            console.log(response);
            //$(“input”).attr(“disabled”, true);
        }
     });  


   
});