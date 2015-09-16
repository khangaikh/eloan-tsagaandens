
// Function that updates all graphs with loss current factor

var updateRowGrafiks =function (id, info){

}


var updateGrafiks = function(loss, response) {
  	var query = new Parse.Query("grafiks");
 	
	query.find({
		success: function(results) {
	  	console.log(results.length);
	  	for (var i = 0; i < results.length; i++) {
	  		
	  		var cur = new Date();
	   		var dateVar = results[i].get("date");
			var dsplit = dateVar.split("-");
			var d = new Date(dsplit[0],dsplit[1]-1,dsplit[2]);
	   		var diff = cur - d;
	   		var days = diff / 1000 / 60 / 60 / 24;

	   		var graph = results[i];
	   		updateRowGrafiks(results[i].get("objectId"), days, response);
	   		//if(days>0){
	   			// var loss_percent = loss/100;
	   			// var left_amount = results[i].get("left_amount");
	   			// var loss_amount = (loss_percent * days * left_amount).toFixed(2);;
	   			/// results[i].set("loss_amount", loss_amount);
	   			// var due_pay = results[i].get("due_pay");
	   			// var due_pay_new = due_pay + loss_amount;
	   			// results[i].set("due_pay", due_pay_new);

	   		//}
	  	}
		},
		error: function() {
		  response.error("Failed");
		}
	});
}

Parse.Cloud.define("Logger", function(request, response) {
	console.log(request.params);
	response.success();
});

//Update loss cron

Parse.Cloud.job("loss", function(request, response) {
 	
	var loss = Parse.Object.extend("loss");
	var query = new Parse.Query(loss);
	query.get("7hOOm7UuHu", {
	  success: function(result) {
	    var loss_daily = result.get("daily_loss");
	    updateGrafiks(loss_daily,response);
	  },
	  error: function(object, error) {
	     response.error("Failed : "+ error);
	  }
	})
});
