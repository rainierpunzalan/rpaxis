function success_alert(msg){
  $('.alert-success').show();
  $('.success_msg').text(msg);
}
function error_alert(msg){
  $('.alert-danger').show();
  $('.error_msg').text(msg);
}
function init_script(){
  $('.navbar li').hover(function(){
    console.log($(this))
    $(this).attr("color","blue");
  });
}
