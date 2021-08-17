<div class="container-fluid">
    <div class="fade-in">
        <div class="row">

            <div class="col-md-8 wrapper">
                <div class="message-wrapper-header" id="selected-message" style="display: none;">
                    <div class="media">
                        <div class="media-left mt-auto mb-auto ">
                            <img src="" alt="user-avatar" class="media-object" id="avatar">
                        </div>
                        <div class="media-body">
                            <p class="senderName "></p>
                        </div>

                    </div>


                </div>
                <div class="col-md-12 wrapper " id="messages">

                </div>

            </div>

            <div class="col-md-4 wrapper">
                <div class="chat-header">
                    chat header
                </div>
                <div class="user-wrapper">
                    <ul class="users">
                        @foreach($users as $user)
                        <li class="user" id="{{ $user->id }}">
                            @if($user->unread)
                            <span class="pending">{{$user->unread}}</span>
                            @endif
                            <div class="media">
                                <div class="media-left">
                                    <img src="public/images/{{$user->photo}}" alt="user-avatar" class="media-object"
                                        id="sender-avatar">
                                </div>
                                <div class="media-body">
                                    <p class="name">{{$user->name}}</p>
                                    <p class="email"> {{$user->email}}</p>

                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>

        </div>


    </div>
</div>