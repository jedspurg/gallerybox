$(function () {
	
$('#type').change(function() {
   $("#user-selector2").hide();
   $('#user-selector' + $(this).find('option:selected').val()).show();
});

$('#moreLink').bind('change', function () {

   if ($(this).is(':checked'))
     $('#moreLinkText').show();
   else
     $('#moreLinkText').hide();

});



});