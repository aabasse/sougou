$(function(){

	/*$("#form_categorie").change(function(){
		var url = $( "#critereSpecifique" ).attr('url') + "?categ="+$(this).val()+" #critereSpecifique";
		console.log(url);
		$( "#critereSpecifique" ).load( url, function(){
		} );
	})*/

	$("#form_categorie").change(function(){
		$(".critere-specifique .critere").hide();
		$(".spe-"+$(this).val()).parent().css('display', 'inline-block');

		h = $(".critere-specifique > div").height();

		var tl = new TimelineMax();
		tl.to($(".critere-specifique"), 0.2, {autoAlpha:0})
		tl.to($(".critere-specifique"), 0.5, {autoAlpha:1, height:h, ease: Circ.easeInOut });
	})

	$("#form_categorie").trigger('change');


	$("#cont-pagination-recherche a").click(function(e){
		e.preventDefault();
		$("form[name='form']").attr('action', $(this).attr('href'))
		$("form[name='form']").submit();
		
	})


})
	