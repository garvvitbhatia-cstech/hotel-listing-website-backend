$(document).ready(function(){
	var formSubmitted = false;
	$(document).on('click','#contact_btn',function(){	
		var flag = 0;	
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;	
		if($.trim($("#cfname").val()) == ''){	
			flag = 1;	
			swal("Error!", 'Please Enter Full Name.', "error");	
			return false;	
		}
		if($.trim($("#ccontact").val()) == ''){	
			flag = 1;	
			swal("Error!", 'Please Enter Contact.', "error");	
			return false;	
		}	
		if($.trim($("#cemail").val()) == ''){
			flag = 1;	
			swal("Error!", 'Please Enter Email.', "error");	
			return false;	
		}else{	
			if(!regex.test($("#cemail").val())){
				flag = 1;	
				swal("Error!", 'Please Enter Valid Email.', "error");
				return false;		
			}	
		}
		if($.trim($("#cmessage").val()) == ''){	
			flag = 1;	
			swal("Error!", 'Please Enter Message.', "error");	
			return false;	
		}	
		if(flag == 0){	
			$('#contact_btn').html('Processing...');	
			$('#contact_btn').attr('disabled',true);	
			$.ajax({					
				url: saveDataURL,	
				type:'POST',
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data: $('.contact-form').serialize(),
				dataType: 'JSON',
				success:function(response){
					$('.field_error').remove();
					if (response.status == 'error'){
						swal("Error!", 'Error on send enquiry.', "error");
						if(response['errors'] != ''){
							$.each(response['errors'], function(key, value){
								$("."+key).slideDown('slow').after().show(0);
								var flag = $('#'+key).parents('.input:first').find('div:first').length;
								if(flag == 0){
									var error_html = '<div class="field_error" id="'+key+'Error" for="'+key+'" generated="true" style="text-align: left;display: inline-block;">'+value+'</div>';
									$("."+key).slideDown('slow').after(error_html);
								}else{
									$("."+key).parents('.input:first').find('div:first').html(value).show(0);
								}
							});
						}
					}else{
						$($('.contact-form')[0].reset());
						swal("Success!", 'Thank you for contacting us.', "success");
					}
					$('#contact_btn').html('Send Message');
					$('#contact_btn').attr('disabled',false);
				}
			});
			return false;
		}
	});
});