jQuery(document).ready(function($){
	$(document).delegate('#less-textarea', 'keydown', function(e) {
	  var keyCode = e.keyCode || e.which;
	
	  if (keyCode == 9) {
	    e.preventDefault();
	    var start = $(this).get(0).selectionStart;
	    var end = $(this).get(0).selectionEnd;
	
	    // set textarea value to: text before caret + tab + text after caret
	    $(this).val($(this).val().substring(0, start)
	                + "\t"
	                + $(this).val().substring(end));
	
	    // put caret at right position again
	    $(this).get(0).selectionStart =
	    $(this).get(0).selectionEnd = start + 1;
	  }
	});
	
	if(initLessEditor){
		var less_editor = CodeMirror.fromTextArea(document.getElementById("less-textarea"), {
	        theme: "eclipse",
	        mode: "text/x-less",
	        lineNumbers : true,
	        matchBrackets : true
	    });
    }
    
    if(initHtmlEditor){
	    var less_editor = CodeMirror.fromTextArea(document.getElementById("content"), {
	        theme: "eclipse",
	        mode: "text/html",
	        lineNumbers : true,
	        matchBrackets : true,
	        autoCloseTags: true
	    });
    }

});

function open_add_page_tab_dialog(){
	var appID = jQuery("#facebook_appid").val();
	var url = "https://www.facebook.com/dialog/pagetab?app_id="+appID+"&display=popup&next=http://www.devserv.de";
		
	var popup = window.open(url, "add_page_tab", "width=500,height=200,status=yes,scrollbars=yes,resizable=yes");
	popup.focus();
}