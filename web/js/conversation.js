$(function(){
	var audioMessage = $("#audioMessage").get(0);
	var topPct = 100;

	$("#contMessages").mCustomScrollbar({
		theme:"dark-thin",
		setTop: $("#contMessages .message").last().position().top+'px',
		//scrollbarPosition: "inside",
		callbacks:{
		    onScroll:function(){
		       //
		       //console.log(this.mcs);
		       topPct = this.mcs.topPct;
		    	if(topPct > 80)
				{
					$("#notifNewMessage").hide(200);
				}
		    }
		}
	});

	$('.smiley-1').click(function(){
		$("#contMessages").mCustomScrollbar("scrollTo",$("#contMessages .message").last());

		//console.log($("#contMessages").css('height') );
	})

	/*$( "textarea" ).keypress(function() {
	  $("form").submit();
	});*/



// returns a char's Unicode codepoint, of the char at index idx of string str
// 2013-07-16 from https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/String/charCodeAt
function fixedCharCodeAt (str, idx) {
    // ex. fixedCharCodeAt ('\uD800\uDC00', 0); // 65536
    // ex. fixedCharCodeAt ('\uD800\uDC00', 1); // 65536
    idx = idx || 0;
    var code = str.charCodeAt(idx);
    var hi, low;
    if (0xD800 <= code && code <= 0xDBFF) { // High surrogate (could change last hex to 0xDB7F to treat high private surrogates as single characters)
        hi = code;
        low = str.charCodeAt(idx+1);
        if (isNaN(low)) {
            throw 'High surrogate not followed by low surrogate in fixedCharCodeAt()';
        }
        return ((hi - 0xD800) * 0x400) + (low - 0xDC00) + 0x10000;
    }
    if (0xDC00 <= code && code <= 0xDFFF) { // Low surrogate
        // We return false to allow loops to skip this iteration since should have already handled high surrogate above in the previous iteration
        return false;
        /*hi = str.charCodeAt(idx-1);
        low = code;
        return ((hi - 0xD800) * 0x400) + (low - 0xDC00) + 0x10000;*/
    }
    return code;
}

function HtmlEncode(s)
{
  var el = document.createElement("div");
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  return s;
}
	

	$("form[name='supprimerMessage']").submit(function(e){

	    e.preventDefault();
	    url = $(this).attr('action');
	    
		var formSerialize = $(this).serialize();
		var contMessageform = $(this).parent().parent();
		contMessageform.hide();
		$.post( url,  formSerialize, function(data) {
		}).done(function(){
			
		}).fail(function() {
			contMessageform.show();
			alert('Message non supprimé. veuillez ressayer (vérifiez votre connexion internet)');
		})
	});



	$("form[name='message']").submit(function(e){

	    e.preventDefault();
	    url = $(this).attr('action');
	    
	    var contenu = $("#option textarea").val();

	    contenuConverti = '';
	    for(var i = 0; i<= contenu.length-1; i++)
	    {
	    	var codepoint = fixedCharCodeAt(contenu, i);
	    	if(codepoint != false)
	    	{
				codepoint = '' + codepoint;
		    	if(codepoint.length > 4)
		    	{
		    		contenuConverti += '&#'+codepoint+';';
		    	}
		    	else
		    	{
		    		contenuConverti += String.fromCodePoint(codepoint);
		    	}
	    	}
	    	
	    }
		$("#option textarea").val(contenuConverti);
		var formSerialize = $(this).serialize();
	    //alert( fixedCharCodeAt(contenu, 0) )
	    $("#option textarea").val('');

		var message = $(getHtmlMessage({}, contenu));
		$('#contMessages .mCSB_container').append(message);
		
		$("#nbMessage").text( parseInt($("#nbMessage").text()) + 1  );

		var tl = new TimelineMax();
		tl.from($(".nvoMess"), 1, {right:-100, autoAlpha: 0, ease: Circ.easeInOut })
		$(".nvoMess").removeClass('nvoMess');

		//$("#contMessages").mCustomScrollbar("scrollTo",$("#contMessages .message").last());
		$("#contMessages").mCustomScrollbar("scrollTo",'bottom');

		$.post( url,  formSerialize, function(data) {
			console.log(data)
	  		if(data['pasErreur'])
	  		{
	  			console.log($(".contDate").first())
	  			$(".contDate").first().text(data['message'].created.date).removeClass("contDate");
	  			//console.log(data['message'])
	  		}
	  		else
	  		{
	  			//console.log('nooooo');
	  		}
		}).fail(function() {
			$(".contDate").first().html("<span class='m-erreur'><i class='fa fa-exclamation-triangle'></i> Message non envoyé. veuillez ressayer (vérifiez votre connexion internet)</span>").removeClass("contDate");
		})
	});

	$("#option #btEnvoyer, #contMessages").click(function(){
		var tl = new TimelineMax();
		tl.to($("#option textarea"), 0.3, {height: '44px', ease: Circ.easeInOut })
	})


	$("#option textarea").focus(function(){
		var tl = new TimelineMax();
		tl.to($(this), 0.3, {height: '200px', ease: Circ.easeInOut }, 0)
	})
	/*
	$("#option textarea").focusout(function(){
		var tl = new TimelineMax();
		tl.to($(this), 0.3, {height: '44px', ease: Circ.easeInOut })

	})*/


	function getHtmlMessage(message, contenu = "")
	{
		var htmlMessage; 
		var classMessage = 'expediteur';
		var align = 'text-right';
		var laDate = '<i class="fa fa-spinner fa-pulse"></i>';
		var classnvoMess = 'nvoMess';
		var contDate = 'contDate';
		if(contenu == "")
		{
			if(message.idExpediteur != $("#contMessages").attr('u') )
			{
				align = 'text-left';
				classMessage = 'destinateur';

			}
			contenu = message.contenu;
			laDate = message.created.date;
			classnvoMess = "";
			contDate = '';
		}
			htmlMessage = "<div class='"+align+" margin-10 contMessage'>"
			htmlMessage +=		"<div class='"+classnvoMess+" message text-left "+classMessage+"'>"
			htmlMessage +=			"<p>"+contenu+"</p>"
			htmlMessage +=			"<p class='text-right "+contDate+"'>"+laDate+"</p>"
			htmlMessage +=		"</div>"
			htmlMessage +=	"</div>";
		return htmlMessage;
	}

	
	getNvoMessage();
	function getNvoMessage(){
		setTimeout(function(){
		$.post( $('#m_link').attr('url') ,  {}, function(data) {
		  		if(data.length > 0)
		  		{
		  			for( var i = 0; i < data.length; i++ )
		  			{
		  				//console.log(data[i]);
		  				ajouterMessage(data[i]);
		  			}
		  		}
		  		
		  		getNvoMessage();
			})
		}, 5000);
	}

	$("#contListEmoji .emoji").click(function(){
		$("#message_contenu").val( $("#message_contenu").val() +$(this).text());
	})


	function ajouterMessage(message)
	{
		audioMessage.play();
		var message = $(getHtmlMessage(message));
		$('#contMessages .mCSB_container').append(message);
		if(topPct > 80)
		{
			$("#contMessages").mCustomScrollbar("scrollTo",'bottom',{
			    scrollInertia:200
			});
		}
		else
		{
			$("#notifNewMessage").show(200);
		}

		var tl = new TimelineMax();
		tl.from($(message), 0.5, {x:-100, autoAlpha: 0, ease: Circ.easeInOut })
		//$(".nvoMess").removeClass('nvoMess');
		
	}

	var h_showListEmoji = 0;
	$("#showListEmoji").click(function(){
		h_showListEmoji = h_showListEmoji == 0 ? 76 : 0;
		var tl = new TimelineMax();
		tl.to($("#contListEmoji"), 0, {paddingTop:'20px', ease: Circ.easeInOut })
		.to($("#contListEmoji"), 0.3, {padding:'0px', height: h_showListEmoji, ease: Circ.easeInOut })
	})





})