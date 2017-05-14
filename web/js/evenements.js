$(function(){





items = $('#timeline-container-label-picto').data('items');

items = JSON.parse(items);

console.log(items)
/*
$('#timeline-container-label-picto').timelineMe({
	items:[{"type":"smallItem","label":"mercredi 22 juin 2016, 09:30","shortContent":"dss","fullContent":"sss"},{"type":"smallItem","label":"jeudi 30 juin 2016, 05:30","shortContent":"hy yuu","fullContent":"sss kkk"},{"type":"smallItem","label":"jeudi 30 juin 2016, 05:30","shortContent":"hy yuu","fullContent":"sss kkk"},{"type":"smallItem","label":"jeudi 30 juin 2016, 05:30","shortContent":"hy yuu zaa","fullContent":"sss kkk"},{"type":"smallItem","label":"mardi 05 juillet 2016, 09:30","shortContent":"amore da","fullContent":"deer"},{"type":"smallItem","label":"Ajourd'huit \u00c3\u00a0 20:00","shortContent":"tyy","fullContent":"sss"},{"type":"smallItem","label":"mercredi 03 ao\u00fbt 2016, 21:40","shortContent":"balade en mere","fullContent":"hhh"}]
});*/

$('#timeline-container-label-picto').timelineMe({items:items});


})