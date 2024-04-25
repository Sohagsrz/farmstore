@extends('layouts.default')
@section('title', 'Chats')
@section('content')
<div class="container mt-5">
        <div class="row chat-container">
            <!-- Left side: Chats list -->
            <div class="col-md-3 chats-list">
                <div class="chat active">Alif</div>
                <div class="chat">Sajjat</div>
                <!-- Add more chats here -->
            </div>
            <!-- Middle: Messages/Conversations -->
            <div class="col-md-6 messages"> <!-- My message -->
            <div class="message my-message">
                Hello, how can I help you?
            </div>
            <!-- Other user's message -->
            <div class="message other-message">
                Hi there! I have a question about your products.
            </div>
            <!-- My message -->
            <div class="message my-message">
                Sure, feel free to ask anything.
            </div>
            <!-- Other user's message -->
            <div class="message other-message">
                Do you have this product in stock?
            </div>
            <div class="input-group mb-3 fixed-bottom">
                    <input type="text" id="messageInput" class="form-control" placeholder="Type your message...">
                    <button class="btn btn-primary" id="sendButton">Send</button>
                </div>

                <!-- Add more messages here -->
                <!-- Video Call -->
                <video id="localVideo" autoplay muted></video>
                <video id="remoteVideo" autoplay></video>
            </div>
            <!-- Right side: User info -->
            <div class="col-md-3 user-info">
                <h4>User Info</h4>
                <p>Name: Alif</p>
                <p>Email: alif@mail.com</p>
                <!-- Add more user info here -->
                <!-- Video Call -->
                <button id="startButton" class="btn btn-primary">Start Video Call</button>
                <button id="hangupButton" class="btn btn-danger">Hang Up</button>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for video call functionality
        const startButton = document.getElementById('startButton');
        const hangupButton = document.getElementById('hangupButton');
        let localStream;
        let pc1;
        let pc2;

        startButton.onclick = async () => {
            localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            document.getElementById('localVideo').srcObject = localStream;

            pc1 = new RTCPeerConnection();
            pc2 = new RTCPeerConnection();

            localStream.getTracks().forEach(track => pc1.addTrack(track, localStream));

            pc1.onicecandidate = event => {
                if (event.candidate) {
                    pc2.addIceCandidate(new RTCIceCandidate(event.candidate));
                }
            };

            pc2.onicecandidate = event => {
                if (event.candidate) {
                    pc1.addIceCandidate(new RTCIceCandidate(event.candidate));
                }
            };

            pc2.ontrack = event => {
                document.getElementById('remoteVideo').srcObject = event.streams[0];
            };

            const offer = await pc1.createOffer();
            await pc1.setLocalDescription(offer);
            await pc2.setRemoteDescription(offer);

            const answer = await pc2.createAnswer();
            await pc2.setLocalDescription(answer);
            await pc1.setRemoteDescription(answer);
        };

        hangupButton.onclick = () => {
            localStream.getTracks().forEach(track => track.stop());
            pc1.close();
            pc2.close();
        };
        // JavaScript for sending messages
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');

        sendButton.onclick = () => {
            const message = messageInput.value;
            // Implement sending message logic here
            console.log('Sending message:', message);
            messageInput.value = ''; // Clear input field after sending
        };
        
    </script>


@stop
@push('css')
<style> .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
        }
        #messageInput {
            width: calc(100% - 100px); /* Adjusted width for the message input field */
            margin-right: 10px;
        }
        .my-message {
            background-color: #007bff;
            color: #fff;
            align-self: flex-end;
        }
        .other-message {
            background-color: #f0f0f0;
            color: #000;
            align-self: flex-start;
        }
        /* Custom CSS for chat interface */
        .chat-container {
            max-width: 1000px;
            margin: auto;
        }
        .chats-list {
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            height: 100vh;
            overflow-y: auto;
        }
        .chat {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #dee2e6;
        }
        .chat.active {
            background-color: #e9ecef;
        }
        .user-info {
            background-color: #f8f9fa;
            border-left: 1px solid #dee2e6;
            height: 100vh;
        }
        .messages {
            background-color: #fff;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            height: 100vh;
            overflow-y: auto;
        }
        #localVideo, #remoteVideo {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
@endpush
@push('js') 

@endpush
        