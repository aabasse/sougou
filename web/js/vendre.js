$(function(){
	
	/*attacherEven();
	function attacherEven()
	{
		$("select[name*='sousCategorie']").change(function(){
			var url = window.location.href + "?typeAnnonce="+$(this).val()+" #contForm";
			console.log(url);
			$( "#contForm" ).load( url, function(){
				attacherEven();
				attacherEvenInputFile();
			} );
		})

	}*/


/* ============================= AFFICHE UN APERCU D'UNE IMAGE TELECHARGER */

	attacherEvenInputFile();
	var srcImgDefaut = -1;
	function attacherEvenInputFile(){
		$("input[type='file']").change(function(){
			//var imgApercu = $(".contImgApercu img", $(this).parent());
			var imgApercu = $(".contImgApercu img",  $(this).closest(".contInputImage") );
			if(srcImgDefaut == -1)
			{
				srcImgDefaut = imgApercu.attr('src');
			}
			var allowedTypes = ['png', 'jpg', 'jpeg', 'gif'];
			var fileInput = this.files[0];

			imgType = fileInput.name.split('.');
		    imgType = imgType[imgType.length - 1].toLowerCase();
			//console.log(this.files[0]);
			if (allowedTypes.indexOf(imgType) != -1) {
				var reader = new FileReader();
				reader.addEventListener('load', function() {
			    	remplacerImageApercu(imgApercu, reader.result, fileInput.name)
			    });
				reader.readAsDataURL(fileInput);
			}
			else
			{
				alert("Ce n'est pas une image :(");
				remplacerImageApercu(imgApercu, srcImgDefaut, 'ajouter une image')
			}
		})

		$(".contImgApercu").click(function(){
			var parent = $(this).parent( ".contInputImage" )
			$('label', parent).trigger( "click" );
		})
	}


	function remplacerImageApercu(imgApercu, src, alt)
	{
		imgApercu.hide(200, function(){
			imgApercu.attr('src', src);
			imgApercu.attr('alt', alt);
		}).show(200);
	}

})

