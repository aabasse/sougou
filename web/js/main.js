$(function(){
	
	$(".flash-message").each(function(){
		var n = noty({
            text        : $(this).html(),
            type        : $(this).data('type'),
            dismissQueue: true,
            layout      : 'topRight',
            closeWith   : ['click'],
            theme       : 'zibonzahe',
            maxVisible  : 6,
            animation   : {
                open  : 'animated bounceInRight',
                close : 'animated bounceOutRight',
                easing: 'swing',
                speed : 500
            }
        });
    })
	
	$(document).ready( function() {
		//getNotification(); 
		function getNotification(){
			setTimeout(function(){
				var nbr = $("#notification").text();
				$.post( "http://localhost:8000/notifications/nbr",  {'nbr':nbr}, function(data) {
			  		if(data['nbr'] > 0)
			  		{
			  			$("#notification").text(data['nbr']);
			  		}
			  		else
			  		{
			  			$("#notification").text('');
			  		}
			  		getNotification();
				})
			}, 5000);
		}
	});

/*========================================= RAFRECHIRE UNE PAGE QUAND ON CLICK SUR PRÉCÉDENT */
	var refreshed = $("#refreshed");
	if(refreshed.val() != undefined)
	{
		if(refreshed.val()=="no")
		{
			refreshed.val("yes");
		}
		else{
			refreshed.val("no");
			location.reload();
		}
	}
/*========================================= FIN */

/*=========================== ICONE FILLED ======*/
	$(".lb-fille").click(function(){
		$(".lb-fille[groupe='"+$(this).attr('groupe')+ "'] .ico-fille").removeClass("filled");
		$(".ico-fille", this).addClass("filled");
	})

	/*========================= AFFICHER ET CACHER ========*/
	$("input[type='radio'][cacher]").change(function(){
		var selecteur = $(this).attr("cacher");
		$(selecteur).hide();
	})

	$("input[type='radio'][afficher]").change(function(){
		var selecteur = $(this).attr("afficher");
		$(selecteur).show();
	})

	$("input[type='radio'][afficher]").each(function(){
		if($(this).prop('checked'))
		{
			$(this).trigger('change');
		}
	})

	$("input[type='radio'][cacher]").each(function(){
		if($(this).prop('checked'))
		{
			$(this).trigger('change');
		}
	})
	
/* PARTIE CONNEXION ==========================================================================*/
	$(".i-effet").click(function(){
		var tl = new TimelineMax();
		tl.to($(this), 0.1, {width:'20px', height:'20px', ease: Circ.easeInOut })
		  .to($(this), 0.2, {width:'30px', height:'30px'});
	})

	$(".blockAnim").click(function(){
		var subThis = this
		$(".blockAnim").each(function(){

			if(this == subThis)
			{
				var tl = new TimelineMax();
				tl.to($(this), 0.2, {'zIndex':'1', 'transform':'rotate(0deg)'});
				
			}
			else
			{
				var tl = new TimelineMax();
				tl.to($(this), 0.2, {'zIndex':'0', 'transform':'rotate(-31deg)'});

			}
		})
	})
/* ================================= TOOLTIP  =============================*/
	$('[data-toggle="tooltip"]').tooltip(); 


if( $("#evenement_edit_DateLieuFormat, #evenement_datelieu").length > 0 )
{
	$("#evenement_edit_DateLieuFormat, #evenement_datelieu").datetimepicker({
  	'showClose': true,
  	//'format':'YYYY-MM-DD HH:mm:ss'
	});
	$('#evenement_edit_DateLieuFormat, #evenement_datelieu').data("DateTimePicker").minDate(moment());
}
	






})

$( '#listCommune a' ).mouseover(function(){
	afficher($(this).attr('for'));
});

$( '#listCommune a' ).mouseout(function(){
	cacher($(this).attr('for'));
});


function afficher(leID)
{
	//$('.dd').clearQueue();
	leID = "#"+leID;
	//$(leID).fadeIn(400);
	var commune = $(leID).attr('alt')
	//alert(commune)
	$('#nomCom_map p').text(commune);

	$( '#listCommune a:contains("'+commune+'")' ).addClass('nomComHover');
	//.css( {"text-decoration":"underline", "color":"#f1c40f"} );
	$('#nomCom_map').show();
	
	//$(leID).css('display', 'block');

	var tl = new TimelineMax();
	//tl.from($(leID), 1, {y : -10, ease: Elastic.easeOut.config(1, 0.3)}, 0);
	tl.to($(leID), 0.2, {autoAlpha : 1}, 0);
	tl.to($(leID), 0, {y : 0});

	
	//tl.to($(leID), 0.2, {autoAlpha : 1}, 0);
	//tlListCommune.to($(leID), 0, {y : 0});
}
function cacher(leID)
{
	//$('.dd').clearQueue();
	leID = "#"+leID;
	//$(leID).fadeOut(100);
	$('#nomCom_map').hide();
	
	//$(leID).css('display', 'none');
	var tl = new TimelineMax();
	tl.to($(leID), 0.5, {autoAlpha : 0});
	$( '#listCommune a' ).removeClass('nomComHover');
	
}

var tlListCommune = new TimelineMax();
tlListCommune.from($("#listCommune"), 1.5, {y : -100, ease: Elastic.easeOut.config(1, 0.3)});

$("#listCommune > div").mCustomScrollbar({theme:"dark-thin", scrollButtons:{ enable: true }});
//$(".contListCateg > div").mCustomScrollbar({theme:"dark-thin"});
$(".contCateg").mCustomScrollbar({theme:"dark-thin", scrollButtons:{ enable: true } });

$(".listClassement > div").mCustomScrollbar({theme:"dark-thin", axis:"x", scrollButtons:{ enable: true }});
$("#mes-annonces > div").mCustomScrollbar({theme:"dark-thin"});
$("#list-coup-de-coeur > div").mCustomScrollbar({theme:"dark-thin", scrollButtons:{ enable: true }});




activerSousCategorie();
function activerSousCategorie()
{
	var categActive = $(".contCateg").data('active-categ');
	if(categActive)
	{
		//$('#categ-'+categActive).addClass('active');
		$(".contCateg").mCustomScrollbar("scrollTo", $('#categ-'+categActive).parent().parent().parent() );	
	}	
}

/*================================================= Affiche Action =======================================*/
$(".afficheAction").click(function(){
	var classAffiche = $(this).data('class-affiche');
	var elementAaffiche = $("."+classAffiche);


	if(elementAaffiche.data('open') == 0)
	{
		afficherEle(elementAaffiche, $(this))
	}
	else
	{
		cacherEle(elementAaffiche, $(this))
	}
})

function afficherEle(el, bt)
{
	var h = el.data('affiche-h');
	el.data('open', 1)
	el.show();
	$(bt).html($(bt).data('text-cache'));
	var tl = new TimelineMax();
	tl.to(el, 0.5, {height : h});
}

function cacherEle(el, bt)
{
	var h = '0px';
	el.data('open', 0)
	el.show();
	$(bt).html($(bt).data('text-affiche'));
	var tl = new TimelineMax();
	tl.to(el, 0.5, {height : h});
	tl.to(el, 0.5, {display : 'none'});
}

/*

$( window ).resize(function() {
	$(".afficheAction").each(function(){
		var classAffiche = $(this).data('class-affiche');
		var elementAaffiche = $("."+classAffiche);

		if( $( window ).width() > 766)
		{
			afficherEle(elementAaffiche, $(this))
		}
		else
		{
			cacherEle(elementAaffiche, $(this))
		}

	})
	// pour menu
	var menu = $("#mainMenu");
	if( $( window ).width() > 766)
	{
		afficherObjet(menu)
	}
	else
	{
		cacherObjet(menu)
	}
});

*/

/*=========================== affiche menu =====================*/
$(".bt-affiche-menu").click(function(){
	var menu = $("#mainMenu");
	if(menu.height() == '0px' || menu.height() == 0)
	{
		afficherObjet( menu );
	}
	else
	{
		cacherObjet( menu );
	}
})

function afficherObjet(el)
{
	var h = $("#mainMenu > div").height();
	var tl = new TimelineMax();
	tl.to(el, 0.5, {height : h});
}

function cacherObjet(el)
{
	var tl = new TimelineMax();
	tl.to(el, 0.5, {height : '0px'});
}