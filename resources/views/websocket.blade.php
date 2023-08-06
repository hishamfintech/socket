<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <h1>Socket Demo</h1>

    <div class="row">
        <div class="col border">
            <h2>Chat</h2>
            <hr/>
            <div class="row">
                <div class="col">
                    <div class="messages">
                    </div>
                </div>
            </div>
            <div class="row p-3 border">
                <div class="col-2">
                    <label class="control-label">
                        Message
                    </label>
                </div>
                <div class="col-8">
                    <input class="form-control" type="text" name="message">
                </div>
                <div class="col-2">
                    <button type="button" class="btn-send-message btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
        <script>
            /*
            * connect to socket
            * */
            myUserId = '';
            messageToUserId = '';
            ws = new WebSocket('ws://localhost:8080');
            ws.onopen = function () {
                if(myUserId==''){
                    myUserId = prompt('Enter User Id:');
                }
                if(messageToUserId==''){
                    messageToUserId = prompt('Enter Other Person\'s User Id:');
                }
                ws.send(JSON.stringify({'action': 'register', 'value': myUserId}));
                $('.messages').append(`<div class="alert alert-info">Connected With Socket, Registering User!</div>`);
            }
            ws.onclose = function () {
                $('.messages').append(`<div class="alert alert-danger">Connection Closed! Bye!</div>`);
            }
            ws.onerror = function () {
                $('.messages').append(`<div class="alert alert-danger">Opps! Error in Connection!</div>`);
            }
            ws.onmessage = function (message) {
                data = JSON.parse(message.data);
                switch (data.action) {
                    case 'error':
                        $('.messages').append(`<div id="${data.value}" class="alert alert-danger">${data.value}</div>`);
                        break;
                    case 'message':
                        let msgTmpl  = `<div class="row">
                                        <div class="col-2">
                                            ${messageToUserId}
                                        </div>
                                        <div class="col-10">
                                            <div class="alert alert-primary">${data.value}</div>
                                        </div>
                                    </div>`;
                        $('.messages').append(msgTmpl);
                        break;
                    default:
                        break;
                }
            }
            $('.btn-send-message').bind('click', function () {
                let message = $('input[name=message]');
                ws.send(JSON.stringify({'action': 'message','to':messageToUserId,'value':message.val()}));
                let msgTmpl  = `<div class="row">
                                        <div class="col-2">
                                            ${myUserId}
                                        </div>
                                        <div class="col-10">
                                            <div class="alert alert-dark">${message.val()}</div>
                                        </div>
                                    </div>`;
                $('.messages').append(msgTmpl);
                message.val('');
            });
        </script>
</html>
