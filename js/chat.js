/**
 * Chat Plug-In
 */
$(document).ready(function() {

    /**
     * Start chat
     */
    index();

    /* _____ Initializing Block _____ */

    var user_name = '';
    var user_input = $('#user_name');
    var message_input = $('#message_input');
    var chat_screen = $('#chat_screen');
    var send_button = $('#send_button');

    /* _____ Events Block _____ */

    user_input.blur(function() {
        user_name = user_input.val();
    });

    send_button.mouseup(function() {
        send();
    });

    message_input.keyup(function(){
        if((event.keyCode || event.which) === 13) {
            send();
        }
    });

    /* ______ Functional Block _____ */

    /**
     * Refresh the chat monitor with up to date messages
     */
    function index() {
        $.post(
            '/server/index.php',
            { action : 'index' },
            function(data) {
                var messages = JSON.parse(data);
                for (var i = messages.length - 1; i >= 0; i --) {
                    m = messages[i];
                    append_message(m.ip, m.message, m.name);
                }
            }
        );
    }

    /**
     * Send and store the message into Db
     */
    function send() {
        if(!checkUser()) return;
        var text = message_input.val();
        if (text) {
            add_message(text);
        } else {
            alert('Supply your message');
            message_input.addClass('is-invalid');
        }
    }

    /**
     * Add message to Db and refresh the chat monitor
     * @param text message body
     */
    function add_message(text) {
        $.post(
            '/server/index.php',
            { action : 'store', name : user_name, message: text },
            function () {
                chat_screen.html('');
                index();
            }
        );
    }

    /**
     * Append to chat screen formatted string for message publish
     * @param ip string of remote or local address
     * @param text sting of message
     * @returns {string}
     */
    function append_message(ip, text, user) {
        var u = user ? user : user_name;
        var m = '<small><strong>' + u + ' ('+ ip + '):</strong><br/>' + text + '</small><br/>';
        chat_screen.append(m);
        user_input.removeClass('is-invalid');
        message_input.removeClass('is-invalid').val('');
    }

    /**
     * Check if the user name is supplied
     * @returns {boolean}
     */
    function checkUser() {
        if(!user_name || user_name.length < 2)
            user_name = user_input.val();
        if(!user_name || user_name.length < 2) {
            alert('Enter user name');
            user_input.addClass('is-invalid');
            return false;
        }
        return true;
    }

});








