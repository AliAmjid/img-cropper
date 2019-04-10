var ImageControl = function () {
	this.init = function () {
		var _this = this;
		$('.image-control').each(function () {
			var id = $(this).attr('id');
			var lbl = $(this).attr('data-label');
			var src = $(this).attr('data-thumbnail');
			var width = $(this).attr('data-width');
			var height = $(this).attr('data-height');
			var scaleX = $(this).attr('data-scale-x');
			var scaleY = $(this).attr('data-scale-y');
			var thumbnailRatio = $(this).attr('data-thumbnail-ratio');
			//creating thumbnail
			var wrapper = $('<span id="img-cropper-span '+id+'-span"></span>');
			var thumbnail = _this.thumbnail(id,src,width,height,thumbnailRatio);
			var label = _this.label(id,lbl);
			var input = _this.input(id);
			var clone = $(this).clone();
			label.append(thumbnail);
			label.append(input);
			wrapper.append(label);
			wrapper.append(clone);
			$(this).replaceWith(wrapper);
			label.tooltip();
			//-- initing modal
			$('body').append(_this.modal(id,src));
			var $modal = $('#' + id + '-modal');
			var image = document.getElementById(id+'-image');
			document.getElementById(id+'-upload').addEventListener('change',function (e) {
				var files = e.target.files;
				var done = function (url,id) {
					input.val('');
					$('#'+id+'-image').attr('src',url);
					$('#' + id + '-modal').modal({backdrop: 'static', keyboard: false});
				};
				//------------
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
					file = files[0];
					if (URL) {
						done(URL.createObjectURL(file),id);
					} else if (FileReader) {
						reader = new FileReader();
						reader.onload = function (e) {
							done(reader.result,id);
						};
						reader.readAsDataURL(file);
					}
				}
				//----
				$('#'+id+'-confirm').click(function () {
					var initialAvatarURL;
					var canvas;
					$modal.modal('hide');
					if (cropper) {
						canvas = cropper.getCroppedCanvas({
							width: width,
							height: height,
						});
						var dataUrl = canvas.toDataURL();
						initialAvatarURL = $('.'+id+'-thumbnail').attr('src',dataUrl);
						$('#'+id).attr('value',dataUrl.split(',')[1]);
					}
				});
			});

			$modal.on('shown.bs.modal', function () {
				var ratio = width / height;
				cropper = new Cropper(image, {
					aspectRatio: ratio,
					viewMode: 2,
				});
				$('#'+id+'-rotate').click(function () {
					cropper.rotate(45);
				});
			}).on('hidden.bs.modal', function () {
				cropper.destroy();
				cropper = null;
			});

		});
	};

	this.thumbnail = function (id,src,width,height,ratio) {

		return $(
			'<img class="img-cropper-thumbnail '+id+'-thumbnail" src="'+src+'" width="'+width*ratio+'" height="'+height*ratio+'" style="max-width: '+width*ratio+'px">'
		);
	};
	this.label = function (id,label) {
	return $('<label class="img-cropper-label '+id+'-label" data-toggle="tooltip" title data-original-title="'+label+'"></label>');
	};
	this.input = function (id) {
		return $('<input type="file" class="img-cropper-upload '+id+'-upload" id="'+id+'-upload" accept="image/*">');
	};

	this.modal = function (id,src) {
			return $(
				'    <div class="modal fade" id="'+id+'-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">  '  +
				'         <div class="modal-dialog" role="document">  '  +
				'           <div class="modal-content">  '  +
				'             <div class="modal-header">  '  +
				'               <h5 class="modal-title" id="'+id+'-modalLabel">Crop the image</h5>  '  +
				'               <button type="button" class="close" data-dismiss="modal" aria-label="Close">  '  +
				'                 <span aria-hidden="true">&times;</span>  '  +
				'               </button>  '  +
				'             </div>  '  +
				'             <div class="modal-body">  '  +
				'               <div class="img-container">  '  +
				'                 <img id="'+id+'-image" src="'+src+'">  '  +
				'               </div>  '  +
				'             </div>  '  +
				'             <div class="modal-footer">  '  +

				'   <div class="mr-auto">  '  +
				'   <button type="button" class="btn btn-primary" id="'+id+'-rotate"> '  +
				'   <i class="fas fa-sync-alt"></i> '  +
				'   </button>  '  +
				'  </div>  ' +
				'             <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>  '  +
				'               <button type="button" class="btn btn-primary" id="'+id+'-confirm">Crop</button>  '  +
				'             </div>  '  +
				'           </div>  '  +
				'         </div>  '  +
				'       </div>  '  +
				'    </div>  '
	);
	}
};
$(document).ready(function () {
	if($('.image-control')) {
		var imgControl = new ImageControl();
		imgControl.init();
	}
});