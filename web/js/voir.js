$(function(){
	//$("#contPetitImg img").elevateZoom();
	//alert($("#contGrandImg").css('width'))

	/*var imageMax = {
		alt : $("#contPetitImg img:first").attr('alt'),
		src : $("#contPetitImg img:first").attr('src')
	}*/

	var imageMax = $("#contPetitImg img.minImg:first");

	if ( $( window ).width() > 766 )
	{
		$("#contPetitImg img.minImg").elevateZoom({
			responsive:true,
			zoomWindowFadeIn:true,
			zoomWindowFadeOut:true,
			zoomWindowPosition: "contGrandImg", 
			borderSize: 1,
			borderColour: '#E6E6E6',
			lensColour:'#6cc5dc',
			easing:true,
			zoomWindowHeight: 300,
			zoomWindowWidth:500
		});
	}
	



	//$("#contGrandImg img").elevateZoom({ zoomType: "inner", cursor: "crosshair" });

	$("#contPetitImg img.minImg").click(function(){
		//imageMax.alt = $(this).attr('alt');
		//imageMax.src = $(this).attr('src');
		imageMax = $(this);
		changerImageMax();
		//console.log(imageMax);
	})

	function changerImageMax()
	{
		$("#contGrandImg img").attr('alt', imageMax.attr('alt'));
		$("#contGrandImg img").attr('src', imageMax.attr('src'));
		var nbrImg = $("#contPetitImg img.minImg").length;



		var indx = imageMax.closest(".contImg").index();
		//console.log(indx)
		if( (indx == 3 && nbrImg == 2  ) || (indx == 5 && nbrImg == 3))
		{
			$("#desactivNextImg").removeClass('hidden');
			$("#nextImg").addClass('hidden');
		}
		else
		{
			$("#nextImg").removeClass('hidden');
			$("#desactivNextImg").addClass('hidden');
		}

		if(indx == 1)
		{
			$("#desactivPrevImg").removeClass('hidden');
			$("#prevImg").addClass('hidden');
		}
		else
		{
			$("#prevImg").removeClass('hidden');
			$("#desactivPrevImg").addClass('hidden');
		}

	}

	$("#nextImg").click(function(){
		//var imgSuiv = imageMax.next().next();
		var imgSuiv = $('img', imageMax.closest(".contImg").nextAll(".contImg") );
		
		if(imgSuiv.hasClass('minImg'))
		{
			imageMax = imgSuiv;
			changerImageMax();
		}
	})

	$("#prevImg").click(function(){
		//var imgSuiv = imageMax.prev().prev();
		var imgSuiv = $('img', imageMax.closest(".contImg").prev().prev(".contImg") );
		//console.log($('img', imageMax.closest(".contImg").prevAll(".contImg")).index() )
		if(imgSuiv.hasClass('minImg'))
		{
			imageMax = imgSuiv;
			changerImageMax();

			/*imgSuiv.is(':last')
			{
				//alert(imgSuiv.attr('alt'))
			}*/
		}
		
	})



	$(".btOuvre").click(function(){
		var selecteur = $(this).attr('for')
		console.log($(selecteur).css('height'))
		//var h = parseInt($(selecteur).css('height')) == 0 ? $(selecteur + " > div").css('height') : '0px';
		var h = parseInt($(selecteur).css('height')) == 0 ? $(selecteur + " > div").outerHeight( true ) : '0px';
		var tl = new TimelineMax();
		tl.to($(selecteur), 0.3, {height:h, ease: Circ.easeInOut })
	})

	//$("#contGrandImg img").elevateZoom({ zoomWindowFadeIn:true, zoomType	: "inner", cursor: "crosshair" });
	//$("#contGrandImg img").elevateZoom();

	$(".vote").click(function(){
		if($('span', this).hasClass('filled'))
		{
			$('span', this).removeClass('filled')
		}
		else
		{
			var force = $(this).attr('force');
			//alert(force)
			$(".vote[force='"+force+"'] span").removeClass('filled')
			$('span', this).addClass('filled')
		}

		$.ajax( {
		 method: "GET", 
		 url: $(this).attr('url'), 
		 //data: { annonce : $(this).attr('annonce') } 
		})
		  .done(function() {
		    
		}).fail(function() {
		    alert( "error" );
		  })
	})


/*========================================== PARTIE ACHETERUR ==============*/

	$("#form-acheteur").submit(function(e){
	    e.preventDefault();
	    $("#dialog-vendu .erreur").empty();
	    var debErreur = '<i class="fa fa-exclamation-circle" aria-hidden="true"></i> Erreur : ';
	    if( $("#pAcheteur").val() != $("#pAcheteur").attr("pUser") )
	    {
	    	
			var formSerialize = $(this).serialize();
		    //alert(formSerialize);
		    $("#form-acheteur .chargement").show();
		    $.ajax( {
				 method: "GET", 
				 url: $(this).attr('action') + "?"+formSerialize, 
				 //data: { annonce : $(this).attr('annonce') } 
				})
				  .done(function(data) {
				  	$("#form-acheteur .chargement").hide();
				  	if(!data.isOK)
				  	{
				  		$("#dialog-vendu .erreur").html(debErreur + data.message);
				  	}
				  	else
				  	{
				  		window.location.href = $("#form-acheteur").data('url-red');
				  		$( "#dialog-vendu" ).dialog( "close" );

				  	}
				  	//console.log(data)  
				}).fail(function() {
					$("#form-acheteur .chargement").hide();
				    $("#dialog-vendu .erreur").html(debErreur + 'Une erreur est survenue lors de l’envoi. Veuillez réessayer plus tard.');
				})

	    }
	    else
	    {
	    	$("#dialog-vendu .erreur").html(debErreur + 'Vous ne pouvez pas vous metre en tant qu\'acheteur.');
	    }
	    

	})

	dialog = $( "#dialog-vendu" ).dialog({
      autoOpen: false,
      width: 350,
      modal: true,
      /*show: {
        effect: "size",
        duration: 600,
        'easing' : 'easeOutExpo'
      },*/
      hide: {
        effect: "size",
        duration: 600,
        'easing' : 'easeOutExpo'
      },
      buttons: {
        "Envoyer": function(){
        	$("#form-acheteur").submit();
        },
        "Annuler": function() {
          $( "#dialog-vendu" ).dialog( "close" );
        }
      }
    });


	var pseudoDejaRecuperer = false;
	$("#bt-vendu").click(function(){
		if(!pseudoDejaRecuperer)
		{
			$.ajax( {
			 method: "GET", 
			 url: '/pseudos', 
			 //data: { annonce : $(this).attr('annonce') } 
			})
			  .done(function(data) {
			  	$( "#pAcheteur" ).autocomplete({
		      		source: data
		    	});
		    	pseudoDejaRecuperer = true;    
			})
		}
		$( "#dialog-vendu" ).dialog( "open" );
	});



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
	var oldIMAGE = Array(-1, -1, -1);
	function attacherEvenInputFile(){
		$("input[type='file']").change(function(){
			//var oldIMAGE = $("#contImgProfil img").attr('src');
			var contImg = $(this).closest(".contImg");



			var imgApercu = $("img", contImg);
			var num = parseInt($("input[name='num']", contImg).val());
			//alert(num)
			if(oldIMAGE[num-1] == -1)
			{
				oldIMAGE[num-1] = imgApercu.attr('src');
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

				$('.optionEdit', contImg).css('display', 'inline-block');
			}
			else
			{
				remplacerImageApercu(imgApercu, oldIMAGE[num-1], 'ajouter une image')
				$('.optionEdit', contImg).css('display', 'none');
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





$('#contPetitImg').magnificPopup({
		delegate: '.imgToZomm',
		type: 'image',
		closeOnContentClick: false,
		closeBtnInside: false,
		mainClass: 'mfp-with-zoom mfp-img-mobile',
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
			opener: function(element) {
				return element.find('img');
			}
		}
		
	});









})