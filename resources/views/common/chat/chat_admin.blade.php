@extends('layouts.webBase')
@section('css')
<link href="{{ asset('css/chat.css') }}" rel="stylesheet">
@endsection

@section('content')
@include('common.chat.body')
@endsection

@section('javascript')
<!-- JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
var recipient = "";
var my_id = "{{ Auth::id() }}";
var senderName = "";
var avatarSrc = "";
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;
    // var pusher = new Pusher('22e025aaf82f335f344d', {
    //     cluster: 'ap1'
    // });
    // var channel = pusher.subscribe('my-channel');
    // channel.bind('my-event', function(data) {
    //     // alert(JSON.stringify(data));
    //     if (my_id == data.sender) {
    //         $('#' + data.recipient).click();
    //     } else if (my_id == data.recipient) {
    //         if (recipient == data.sender) {
    //             // if recipient is selected, reload the selected user
    //             $('#' + data.sender).click();
    //         } else {
    //             var pending = parseInt($('#' + data.sender).find('.pending').html());
    //             if (pending) {
    //                 $('#' + data.sender).find('.pending').html(pending + 1);
    //             } else {
    //                 $('#' + data.sender).append('<span class="pending">1</span>');
    //             }
    //         }
    //     }
    // });
     Pusher.logToConsole = true;

    var pusher = new Pusher('ab82b7896a919c5e39dd', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        if (my_id == data.sender) {
            $('#' + data.recipient).click();
        } else if (my_id == data.recipient) {
            if (recipient == data.sender) {
                // if recipient is selected, reload the selected user
                $('#' + data.sender).click();
            } else {
                var pending = parseInt($('#' + data.sender).find('.pending').html());
                if (pending) {
                    $('#' + data.sender).find('.pending').html(pending + 1);
                } else {
                    $('#' + data.sender).append('<span class="pending">1</span>');
                }
            }
        }
    });
    $('.user').click(function() {
        $('.user').removeClass('active');
        $(this).addClass('active');
        $(this).find('.pending').remove();
        var senderName = $(this).find('.name').text();
        var avatarSrc = $(this).find('#sender-avatar').attr('src');
        $('#selected-message').show();
        $('#avatar').attr('src', avatarSrc);
        $('.senderName').html(senderName);
        recipient = $(this).attr('id');
        $.ajax({
            type: "get",
            url: "chat/" + recipient,
            data: "",
            cache: false,
            success: function(data, user) {
                $('#messages').html(data);
                scrollToBottom();
            }
        });
    });
    $(document).on('keyup', '.input-text input', function(e) {
        var message = $(this).val();
        //check of enter key is pressed and message is not empty and recipient is selected
        if (e.keyCode == 13 && message != '' && recipient != null) {
            $(this).val('');
            var datastr = "recipient=" + recipient + "&message=" + message;
            $.ajax({
                type: "post",
                url: "chat",
                data: datastr,
                cache: false,
                success: function(data) {
                    // $('#messages').html(data);
                },
                error: function(jqXHR, status, err) {
                    // alert(jqXHR.err);
                },
                complete: function() {
                    scrollToBottom();
                }
            })
        }
    });
});

function scrollToBottom() {
    $('.message-wrapper').animate({
        scrollTop: $('.message-wrapper').get(0).scrollHeight
    }, 50);
}

$(document).ready(function() {
    $('#search-text').keyup(function() {
        var text = $(this).val();
        if (text.length >= 2) {
            $.ajax({
                url: "search",
                data: {
                    text: text
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#result').html(
                        '<li class="list-group-item">Loading...</li>')
                },
                success: function(res) {
                    console.log(res);
                    var _html = '';
                    $.each(res, function(index, data) {
                        _html += '<li class="user" id="' + data.id +
                            '">' +
                            '@if(' +
                            data.unread + ' != 0)'
                        ' <span class="pending">' + data.unread +
                            '</span>' +
                            '@endif' +
                            '<div class="media">' +
                            '<div class="media-left">' +
                            ' <img src="public/images/' + data.photo +
                            '" alt="user-avatar" class="media-object" id="sender-avatar">' +
                            '</div>' +
                            '<div class="media-body">' +
                            '<p class="name">' + data.name + '</p>' +
                            '<p class="email">' + data.email + '</p>' +
                            '</div>' +
                            '</div>' +
                            '</li>';
                    });
                    $('#result').html(_html);
                    $('.user').click(function() {
                        $('.user').removeClass('active');
                        $(this).addClass('active');
                        $(this).find('.pending').remove();

                        var senderName = $(this).find('.name').text();
                        var avatarSrc = $(this).find('#sender-avatar')
                            .attr('src');

                        $('#selected-message').show();
                        $('#avatar').attr('src', avatarSrc);
                        $('.senderName').html(senderName);

                        recipient = $(this).attr('id');
                        $.ajax({
                            type: "get",
                            url: "chat/" + recipient,
                            data: "",
                            cache: false,
                            success: function(data, user) {
                                $('#messages').html(data);
                                scrollToBottom();
                            }

                        });
                    });
                }
            })
        } else {
            $('#result').html('');
            return false;
        }
    });

});
</script>

@endsection