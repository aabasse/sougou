$(function(){

	var imageMax = $("#contGrandImg img.minImg:first");

/*========================================== PARTIE ACHETERUR ==============*/







	var formAvalider = null;
	var dialogConfirm = $( "#dialog-confirm" ).dialog({
	  autoOpen: false,
      //resizable: false,
      //height:300,
      //width: 460,
      modal: true,
      buttons: {
        "Oui": function() {
        	$( this ).dialog( "close" );
        	envoyerForm(formAvalider);
        },
        "Non": function() {
          $( this ).dialog( "close" );
          formAvalider = null;
        }
      }
    });

    $(".avecConfirm button").click(function(e){
    	e.preventDefault();
    	formAvalider = $(this).parent();
    	dialogConfirm.dialog( "open" );
    })

    function envoyerForm(form){
    	form.submit();
    }

	attacherEvenInputFile();
	var imgApercu = $("#imgEven");
	var oldSrcImage = imgApercu.attr('src');
	function attacherEvenInputFile(){
		$("input[type='file']").change(function(){
			var contImg = $(this).closest(".contImg");
			var contImg = $()		

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

				$('.optionEdit').css('display', 'inline-block');
			}
			else
			{
				remplacerImageApercu(imgApercu, oldSrcImage, 'ajouter une image')
				$('.optionEdit').css('display', 'none');
				alert("Ce n'est pas une image :(");
			}
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