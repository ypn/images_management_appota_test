<!DOCTYPE html>
<html>
<head>
	<title>Manager images</title>
	{{Html::style('css/app.css')}}
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	{{Html::style('css/cropper.min.css')}}	
	{{Html::style('css/main.css')}}	
</head>
<body>
	<main>
		<div class="container main-section">

			<h3 style="margin: 20px 0;font-weight: bold;">Images management</h3>	

			@if(Session::has('err'))
			<div class="alert alert-danger">
			  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			  <strong>Danger!</strong> {{Session::get('err')}}
			</div>	
			@endif	
			
			<div class="select-file">
				{{Form::open(['url'=>'/file/upload','id'=>'upload-form','files'=>true])}}		
				{{Form::file('image',['id'=>'file-upload-invisible','accept'=>'image/*'])}}					
				<button class="btn" id="file-upload-visible">Upload file</button>	
				{{Form::close()}}		
			</div>			
			<div class="list-images">
				<ul>
				@foreach($images as $img)
					<li class="img-wrapper">
						<div class="attactment-preview">
						<a class="thumb-action edit" data-toggle="tooltip" title="Edit" data-name="{{$img->file_name}}" data-action="edit" data-title="{{$img->title}}" data-id="{{$img->id}}"><i class="fa fa-pencil" aria-hidden="true"></i></a>	
						<a class="thumb-action del" data-toggle="tooltip" title="delete" data-name="{{$img->file_name}}" data-action="del" data-id="{{$img->id}}"><i class="fa fa-times" aria-hidden="true"></i></a>												
						<div class="thumb">
							<img src="/uploads/users/thumbs/{{$img->width}}/{{$img->height}}/{{$img->file_name}}">
						</div>
						</div>
					</li>
				@endforeach
				</ul>

			</div>
		</div>
	</main>

	<!--Modal for preview and edit image-->
	<div class="modal" id="img-preview">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">Edit image</h4>
			        <div class="input-group">
      					<label class="input-group-addon" for="dataWidth">Title</label>
      					<input type="text" class="form-control" id="title-image">	
      				</div>
		      	</div>
		      	<div class="modal-body">
			      	<div class="container col-md-12">
			      		<div id="result-crop"></div>
				      	<div class="row" id="crop-area">
				      		<div class="col-md-9">				      			
				      			<div class="crop-area">
		      						<img src="http://placehold.it/500x300">
		      					</div>
				      		</div>
				      		<div class="col-md-3">
				      			<div class="crop-preview"></div>

				      			<!-- Section crop info -->
				      			<div class = "crop-info">		      				
				      				<div class="input-group">
				      					<label class="input-group-addon" for="dataWidth">Width</label>
				      					<input type="text" class="form-control" id="dataWidth">
				      					<span class="input-group-addon">px</span>
				      				</div>

				      				<div class="input-group">
				      					<label class="input-group-addon" for="dataWidth">Height</label>
				      					<input type="text" class="form-control" id="dataHeight">
				      					<span class="input-group-addon">px</span>
				      				</div>
				      			</div><!--End section crop info -->


				      			<!--Crop and edit image-->
				      			<div class="action-edit-image">	   

				      				<div class="btn-group">   				
					      				<button class="btn btn-primary edit-action" data-action="scaleX-left" data-toggle="tooltip" title="scaleX left"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></button>					      			

					      				<button class="btn btn-primary edit-action" data-action="scaleX-right"  data-toggle="tooltip" title="scaleX right"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
					      			</div>
					      			
					      			<div class="btn-group">
					      				<button class="btn btn-primary edit-action" data-action="scaleY-up"  data-toggle="tooltip" title="scaleY up"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></button>

					      				<button class="btn btn-primary edit-action" data-action="scaleY-down"  data-toggle="tooltip" title="scaleY down"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></button>
					      			</div>

					      			<div class="btn-group">
					      				<button class="btn btn-primary edit-action" data-action="rotate-down"  data-toggle="tooltip" title="rotate down"><i class="fa fa-undo" aria-hidden="true"></i></button>

					      				<button class="btn btn-primary edit-action" data-action="rotate-up"  data-toggle="tooltip" title="rotate up"><i class="fa fa-repeat" aria-hidden="true"></i></button>
					      			</div>
				      			</div><!--End section edit-->
				      		</div>
				      	</div>			      		
		      		</div>			      	      		
		      	</div>
		      	<div class="modal-footer">
		      		<button class="btn btn-primary" id="save-edit" data-loading-text="<i class='fa fa-spinner fa-spin '></i>"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
		      		<button class="btn btn-primary" id="trash"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>
		      	</div>

		      	<!--Snackbar-->
				<div id="snackbar">Saved..</div>
			</div>
		</div>
	</div><!--End section modal-->


	{{Html::script('js/app.js')}}
	{{Html::script('js/cropper.min.js')}}
	{{Html::script('js/script.js')}}
	
</body>
</html>