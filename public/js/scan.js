$(document).ready(function(){
	
	var input = $("#barcode");
	
	input.keyup(function(event){
		
		if(input.val().length == 6) {
			$.ajax({
				type: "POST",
				url: "/admin/barcode",
				data: {barcode: input.val()},
				success: function(data){
					
					console.log(data);
					var alertArea = $("#alert-area");
					if(data === "1") {
						alertArea.html('<div class="alert alert-success alert-dismissible fade in"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Success!</strong> Registered Attendance for Student <strong>' + input.val() + "</strong></div>");
					}else {
						alertArea.html('<div class="alert alert-danger alert-dismissible fade in"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Error!</strong> Student Doesnt Exist <strong>' + input.val() + "</strong></div>");
					}
					
					input.val("");
				}
				
			});
		}
	});
});