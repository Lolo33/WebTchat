$( document ).ready(function() {
    recupMessage();
});

function antiSpam(){
    var msg = $('#message');
    msg.val('');
    if (!isRank) {
        msg.attr('readonly', 'readonly');
        msg.blur();
        setTimeout(function () {msg.attr('readonly', false);}, 3000);
    }
}

$('#btn-envoyer').click(function() {
    var message = $('#message').val();
    if(message != ''){
        $.post('inc/snd_message.php',{message:message},function(data){
            recupMessage();
            $('#message-cont').html(data);
            antiSpam();
        });
    }
    return false;
});


$('#form-message').submit(function() {
    var message = $('#message').val();

    if(message != ''){
        $.post('ajax/snd_message.php',{message:message},function(data){
            recupMessage();
            $('#message-cont').html(data);
            antiSpam();
        });
    }
    return false;
});


function recupMessage(){
    $.post('ajax/recup_message.php', function(data){
        $('#message-cont').html(data);
        if ($("#scroll-act").prop('checked'))
            ScrollBas();
    });
}

function ScrollBas(){
    document.getElementById("message-cont").scrollTop = document.getElementById('message-cont').scrollHeight;
}


/*
 A l'appuie d'une touche (eveement)
 document.getElementById('message').onkeypress = function(ev) {
 ev = ev || window.event;
 if (ev.keyCode == 13){

 }
 }*/

setInterval(recupMessage,2000);