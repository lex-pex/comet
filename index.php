<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>  Comet Chat & Timer  </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="/js/chat.js"></script>
    <script src="/js/timer.js"></script>
</head>
<body>
<div class="jumbotron text-center">
    <div class="row">
        <div class="offset-md-9 col-md-3 offset-sm-6 col-sm-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="user_name" class="text-muted small">Your Name:</label>
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" id="user_name">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3 class="display-5"> Chat & Timer </h3>
    <p class="text-primary">Here at Google we tent to take another approach...</p>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="chat_screen" class="text-muted small">  Chat log:  </label>
                        <div class="form-control" style="min-height:250px;overflow-y:scroll" readonly id="chat_screen"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-sm-12 pt-2">
                    <input type="text" class="form-control" id="message_input" placeholder="message...">
                </div>
                <div class="col-md-2 col-sm-12 pt-2">
                    <button class="btn btn-block btn-outline-primary" id="send_button">Send</button>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <div class="row p-5">
                    <div class="col-5 text-right">
                        <label for="user_input" class="text-muted small">Timer (Started):</label>
                    </div>
                    <div class="col-7 text-left">
                        <div style="width:95px">
                            <div style="resize:none;height:37px" type="text" class="form-control" id="timer_screen"
                                 readonly><span id="hh">00</span>:<span id="mm">00</span>:<span id="ss">00</span></div>
                        </div>
                    </div>
                </div>
                <div class="row pt-5 text-center">
                    <div class="col-4 text-right">
                        <button class="btn btn-outline-success" id="start_button">Start</button>
                    </div>
                    <div class="col-4 text-center">
                        <button class="btn btn-outline-dark" id="reset_button">Reset</button>
                    </div>
                    <div class="col-4 text-left">
                        <button class="btn btn-outline-danger" id="stop_button">Stop</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr/>
<footer class="footer pt-5 text-center">
    <div class="container">
        <p class="text-muted"> Copyright &copy;<script>document.write(new Date().getFullYear())</script> Lex-Pex</p>
    </div>
</footer>
</body>
</html>



