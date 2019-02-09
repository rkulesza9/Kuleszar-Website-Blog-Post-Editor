//add new tag element
var count=1;

function addTag() {
  var tag_select = $("#tags_div");
  tag_select.append("<select name='tag"+count+"'>");
  fillTagsSelect(); //php defined
  tag_select.append("</select>");
  count++;
}

// rmv last tag element
function rmvTag(){
  if(count <= 1) return;
  count--;
  var name = "*[name=tag"+count+"]";
   $(name).remove();
}

function onSave(){
  var content = $("#editor").html();
  $("#editor-content").html("<input type='text' name='content' value='"+content+"'>'");
  $("#editor-content").hide();
}
