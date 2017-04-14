/* @author: ypn
 * @des: This file is about whole handler event in client of file management.
*/

const $base_url = window.location.origin;
const $input_image = $('#file-upload-invisible'); 
const $form = jQuery('#upload-form');
const $modal = jQuery('#img-preview');
const _token = $('input[name="_token"]').val();
var $string = {
	invalid_name :'Illegal name!',
	waring_delete:'Are you sure you want to delete this file?',
	server_error:'Have some error! Sory for inconvenient',
}

var images_management = {	

	/* setting general */
	init:function(){		
		$('[data-toggle="tooltip"]').tooltip(); 	
		jQuery('#file-upload-visible').click(function(e){							
			$input_image.click();
			$input_image.change(function(){					
				$form.submit();
			});	

			e.preventDefault();			
		});
	},


	/*Open modal when user click edit file*/
	show_preview:function(){

		var origin_url = $base_url + '/uploads/users/origin/';
		var img = $('#img-preview .crop-area img');
		var dataWidth = $('#dataWidth');
		var dataHeight = $('#dataHeight');
		var counter = false;

		/* Edit action */
		$('.edit-action').click(function(){
			var _action = $(this).data('action');

			switch (_action) {
				case 'scaleX-left':
					img.cropper('scaleX',-1);
					break;
				case 'scaleX-right':
					img.cropper('scaleX',1);
					break;
				case 'scaleY-up':
					img.cropper('scaleY',-1);
					break;
				case 'scaleY-down':
					img.cropper('scaleY',1);
					break;
				case 'rotate-down':
					img.cropper('rotate',-15);
					break;
				case 'rotate-up':
					img.cropper('rotate',15);
					break;
				default:					
					break;
			}
		});	

		/*reset cropper area.*/
		$modal.on('hidden.bs.modal',function(){
			img.cropper('destroy');
			$('#result-crop').empty();			
			$('#crop-area').show();
			counter = false;
			$('#trash').html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
		});


		/*When user click edit or delete file*/
		$('.attactment-preview .thumb-action').click(function(){
			var _this = $(this);
			var data_action = $(this).data('action');
			var _id = $(this).data('id');		
			var data_name = $(this).data('name');	
			var thumb = $(this).closest('.attactment-preview').find('img');	
			var thumb_src = thumb.attr('src');			
			$('#img-preview #title-image').val($(this).data('title'));
			switch (data_action) {
				case 'edit':					
					var src = origin_url + data_name + '?' + new Date().getTime();
					img.attr('src',src);	

					//Create cropper with default opptions
					img.cropper('destroy').cropper({				
						preview: '.crop-preview',
						dragMode:'move',
						crop:function(e){
							dataWidth.val(Math.round(e.width));
							dataHeight.val(Math.round(e.height));
						}
					});
					$modal.modal();

					/* Save edit */
					$('#save-edit').unbind().click(function(){
						var pngUrl = img.cropper('getCroppedCanvas').toDataURL(); 
						var btn = $(this);
						var title = $('#title-image').val();

						if(title && title.trim()!="" && title.length < 256){
							$.ajax({
								'url':$base_url + '/cropped',
								'method':'POST',
								data:{
									filename:data_name,
									encode_image:pngUrl,
									_token,
									title:title,
									id:_id
								},
								beforeSend :function(){							
									btn.button('loading');
								},
								success:function(data){	
									if(data == 'success'){
										_this.data('title',$('#img-preview #title-image').val()) ;
										btn.button('reset');								
										thumb.attr('src',thumb_src + '?' + new Date().getTime());
										jQuery('#snackbar').addClass('show');
										setTimeout(function(){$('#snackbar').removeClass('show'); }, 2000);	
									}

									else{
										alert ($string.server_error);
									}														
								}
							});
						}else{
							alert($string.invalid_name);
						}
						
					});

					break;
				case 'del':
					var r = confirm($string.waring_delete);
					var id = $(this).data('id');
					if(r){
						$.ajax({
							url: $base_url + '/files/delete',
							method : 'POST',
							data:{filename:data_name,file_id:id,_token:_token},
							success:function(data){
								console.log(data);
								if(data=='success'){
									location.reload();
								}								
							}
						});
					}					
					break;
				default:
					// statements_def
					break;
			}				
		});

		var preview_cropped = function () {
		    counter = !counter;
	    	if(counter){
	    		$('#trash').html('<i class="fa fa-ban" aria-hidden="true"></i>');
	    		$('#crop-area').hide();
				var canvas = img.cropper('getCroppedCanvas');
		        canvas.toBlob(function(blob){
		            var newImg = document.createElement('img'),
		            url = URL.createObjectURL(blob);

		            newImg.onload = function() {
		                // no longer need to read the blob so it's revoked
		                URL.revokeObjectURL(url);
		            };

		            newImg.src = url;      
		            $('#result-crop').show();     
		            $('#result-crop').html(newImg);	          
		           
		        });
	    	}else{		    	
	    		$('#trash').html('<i class="fa fa-check-square-o" aria-hidden="true"></i>');
	    		$('#result-crop').empty();	
	    		$('#crop-area').show();
	    	}
		};		

		$('#trash').click(function(){
	      	preview_cropped();	              
		});

	},
}

jQuery(document).ready(function(){		
	images_management.init();	
	images_management.show_preview();		
});