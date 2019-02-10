$(document).ready(function(){
  $(".good-edit__form .good-edit__delete").click( function(){
    return confirm('Вы точно хотите удалить этот товар?');
  });
});
