$(function(){$("#form_categorie").change(function(){$(".critere-specifique .critere").hide(),$(".spe-"+$(this).val()).parent().css("display","inline-block"),h=$(".critere-specifique > div").height();var e=new TimelineMax;e.to($(".critere-specifique"),.2,{autoAlpha:0}),e.to($(".critere-specifique"),.5,{autoAlpha:1,height:h,ease:Circ.easeInOut})}),$("#form_categorie").trigger("change"),$("#cont-pagination-recherche a").click(function(e){e.preventDefault(),$("form[name='form']").attr("action",$(this).attr("href")),$("form[name='form']").submit()})});