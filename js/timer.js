/**
 * Timer Plug-In
 */
$(document).ready(function() {

    /**
     * Timer Start Init
     */
    init();

    /**
     * Initialization block
     */
    var start_button = $('#start_button');
    var reset_button = $('#reset_button');
    var stop_button  = $('#stop_button');
    var interval = null;
    var seconds = 0;
    var hh = $('#hh');
    var mm = $('#mm');
    var ss = $('#ss');

    /**
     * Control items initiating with events
     */
    start_button.mouseup(function() { start(0);});
    reset_button.mouseup(function() { reset(); });
    stop_button.mouseup (function() { stop(); });

    /* _____ Event Performing Block _____ */

    /**
     * Check if timer was launched on the other side
     * Get time from start And take it from there
     */
    function init() {
        $.post(
            '/server/index.php',
            { action : 'timer_init' },
            function(data) {
                var d = JSON.parse(data);
                if(d.pause) {
                    pause(d.pause);
                } else if(parseInt(d.seconds) > 0) {
                    start(d.seconds);
                }
            }
        );
    }

    /**
     * Set the timer on pause with saving
     * the amount of counted seconds
     */
    function stop() {
        clearInterval(interval);
        able_start_button();
        $.post(
            '/server/index.php',
            { action : 'timer_pause', seconds : seconds }
        );
    }

    /**
     * Launch the interval incrementing
     * @param start_seconds - time from which the timer is working
     */
    function start(start_seconds) {
        disable_start_button();
        if(start_seconds === 0) {
            set_new_timer();
        } else {
            seconds = Math.floor(new Date().getTime() / 1000) - start_seconds;
            counter();
        }
    }

    /**
     * Hold on the timer on page open
     * @param amount - freeze time in seconds
     */
    function pause(amount) {
        seconds = amount;
        disable_stop_button();
        compute_display();
    }

    /**
     * Reset the incremented this.seconds field,
     * the html time-monitor, seconds and pause db cells
     */
    function reset() {
        clearInterval(interval);
        able_start_button();
        able_stop_button();
        $.post(
            '/server/index.php',
            { action : 'timer_reset' }
        );
        sedonds = 0;
        ss.html('00');
        mm.html('00');
        hh.html('00');
    }

    /**
     * Fixate the time of start and launch the counter
     */
    function set_new_timer() {
        var start_seconds = Math.floor(new Date().getTime() / 1000);
        $.post(
            '/server/index.php',
            { action : 'timer_start', start_seconds : start_seconds },
            function() {
                counter();
            }
        );
    }

    /**
     * Increment of the seconds and display it in time format
     * Initialize the interval field, for access to stop it
     */
    function counter() {
        interval = setInterval(function() {
            seconds ++;
            compute_display();
        }, 1 );
    }

    /* _____ Visual Effects _____ */

    function compute_display() {
        var s = seconds % 60;
        var m = Math.floor(seconds / 60)  % 60;
        var h = Math.floor(seconds / 3600);
        ss.html((s < 10 ? '0' : '') +  s);
        mm.html((m < 10 ? '0' : '') +  m);
        hh.html((h < 10 ? '0' : '') +  h);
    }

    function disable_start_button() {
        able_stop_button();
        start_button.removeClass('btn-outline-success').addClass('btn-success');
    }

    function able_start_button() {
        disable_stop_button();
        start_button.removeClass('btn-success').addClass('btn-outline-success');
    }

    function disable_stop_button() {
        stop_button.removeClass('btn-outline-danger').addClass('btn-danger');
    }

    function able_stop_button() {
        stop_button.removeClass('btn-danger').addClass('btn-outline-danger');
    }

});

















