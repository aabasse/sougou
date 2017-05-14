$(function(){

var tlImgProfil = new TimelineMax();
tlImgProfil.from($("#contImgProfil img"), 1.5, {autoAlpha: 0, y:100, ease: Circ.easeInOut })

/* ============================= AFFICHE UN APERCU D'UNE IMAGE TELECHARGER */

	var oldIMAGE = $("#contImgProfil img").attr('src');
	attacherEvenInputFile();
	function attacherEvenInputFile(){

		$("input[type='file']").change(function(){
			
			var imgApercu = $("#contImgProfil img");
			//console.log(imgApercu)
			var allowedTypes = ['png', 'jpg', 'jpeg', 'gif'];
			var fileInput = this.files[0];

			imgType = fileInput.name.split('.');
		    imgType = imgType[imgType.length - 1].toLowerCase();

			if (allowedTypes.indexOf(imgType) != -1) {
				var reader = new FileReader();
				reader.addEventListener('load', function() {
			    	remplacerImageApercu(imgApercu, reader.result, fileInput.name)
			    });
				reader.readAsDataURL(fileInput);

				$('.optionEdit').css('display', 'inline-block');
			}
			else
			{
				remplacerImageApercu(imgApercu, oldIMAGE, 'ajouter une image')
				$('.optionEdit').css('display', 'none');
				alert("Ce n'est pas une image :(");
			}
			$('.optionEdit').css('display', 'inline-block');
		})

		/*$(".modifImage").click(function(){
			$('#lbImgProfil').trigger( "click" );
		})*/
	}


	function remplacerImageApercu(imgApercu, src, alt)
	{
		imgApercu.hide(200, function(){
			imgApercu.attr('src', src);
			imgApercu.attr('alt', alt);
		}).show(200);
	}




})

