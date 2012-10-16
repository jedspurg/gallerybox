$(function () {
  $("a[rel=twipsy]").twipsy({
	live: true
  });
  
  $('#gbxImg').imgNotes(); 
  
  $('#cancelnote').click(function(){
	  $('#gbxImg').imgAreaSelect({ hide: true });
	  $('#noteform').hide();
  });

  $('#addnotelink').click(function(){
	  $('#gbxImg').imgAreaSelect({ onSelectChange: showaddnote, x1: 120, y1: 90, x2: 280, y2: 210 });
	  return false;
  });
  
  $('.noteIDcopy').click(function(){
	  var noteToDelete = $(this).attr('id');
	  $("#noteID").val(noteToDelete);
  });
  
  $('.commentIDcopy').click(function(){
	  var commToDelete = $(this).attr('id');
	  $("#comID").val(commToDelete);
  });
  

  
  $("#imgComment").focus(function() {

		if( $(this).text() == "add your comment here..." ) {
			$(this).text("");
		}

	});
	  
});
		
function showaddnote (img, area) {
	imgOffset = $(img).offset();
	form_left  = parseInt(imgOffset.left) + parseInt(area.x1);
	form_top   = parseInt(imgOffset.top) + parseInt(area.y1) + parseInt(area.height)+5;

	$('#noteform').css({ left: form_left + 'px', top: form_top + 'px'});

	$('#noteform').show();

	$('#noteform').css("z-index", 10000);
	$('#NoteX1').val(area.x1);
	$('#NoteY1').val(area.y1);
	$('#NoteHeight').val(area.height);
	$('#NoteWidth').val(area.width);

}
	