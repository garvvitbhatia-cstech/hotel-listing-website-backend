$(document).ready(function(){
	var formSubmitted = false;
	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}
	$('#form_submit').click(function(e){
		var flag = 0;
		if(!formSubmitted){
			formSubmitted = false;
			var validateMobNum= /^\d*(?:\.\d{1,2})?$/;
			if($.trim($("#newsletter_name").val()) == ''){
				flag = 1;
				swal("Error!", 'Please Enter Name.', "error");
				return false;
			}
			if($.trim($("#newsletter_email").val()) == ''){
				flag = 1;
				swal("Error!", 'Please Enter Email Address.', "error");
				return false;
			}else{
				if(!isEmail($.trim($("#newsletter_email").val()))){
					flag = 1;
					swal("Error!", 'Please Enter Valid Email Address.', "error");
					return false;
				}
			}
			if(flag == 0){					
				formSubmitted = true;
				$.ajax({
					type: 'POST',
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					url: saveNewsletterDataURL,
					data: {name:$.trim($("#newsletter_name").val()),email:$.trim($("#newsletter_email").val())},
					success: function(msg){
						formSubmitted = false;	
						$('#newsletter_name').val('');
						$('#newsletter_email').val('');
						swal("Success", 'Newsletter Subscribe Successfully.', "success");
						return false;
					},error: function(ts) {
						console.log(ts);
						formSubmitted = false;							
						swal("Error!", 'Something went wrong, please try after sometime.', "error");
						return false;
					}
				});
				return false;
			}
		}
	});
});