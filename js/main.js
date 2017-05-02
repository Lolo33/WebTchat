
var compte_msg = 0;
var compte_time = new Date().getTime();
console.log(compte_time);

$('#form-message').submit(function() {
    var message = $('#message').val();
    var time_snd = new Date().getTime();
    console.log(compte_time - time_snd);
    if(message != ''){
        $.post('ajax/snd_message.php',{message:message},function(data){
            //charger();
            antiSpam();
            $('#message-cont').append(data);

        });
    }
    return false;
});

function charger(){
        //console.log(premierID);
        $.ajax({
            url : "charger.php?id=" + $('#message-cont .message-box:last').attr('id'), // on passe l'id le plus r�cent au fichier de chargement
            type : "GET",
            success : function(html){
               $('#message-cont').append(html);
            }
        });
    }

function ScrollBas(){
    document.getElementById("message-cont").scrollTop = document.getElementById('message-cont').scrollHeight;
}

function antiSpam(){
    var msg = $('#message');
    msg.val('');
    if (!isRank) {
        msg.attr('readonly', 'readonly');
        msg.blur();
        setTimeout(function () {msg.attr('readonly', false);msg.focus();}, 3000);
    }
}

setInterval(charger,500);


