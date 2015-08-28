
// Use Parse.Cloud.define to define as many cloud functions as you want.
// For example:
Parse.Cloud.define("Logger", function(request, response) {
	console.log(request.params);
	response.success();
});

Parse.Cloud.job("loss", function(request, response) {
 	var query = new Parse.Query("grafiks");
	query.find({
	success: function(results) {
	  var date;
	  
	  for (var i = 0; i < results.length; ++i) {
	   date = results[i].get("date");




	  }
	  response.success(sum / results.length);
	},
	error: function() {
	  response.error("Failed");
	}
	});

});
