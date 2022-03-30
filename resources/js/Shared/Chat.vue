<template>
  <div>
    <!-- <form @submit.prevent="createRoom" v-if="addNewRoom">
      <input type="text" placeholder="Группа" v-model="addRoomUsername" />
      <button type="submit" :disabled="disableForm || !addRoomUsername">
        Создать комнату
      </button>
      <button class="button-cancel" @click="addNewRoom = false">
        Отмена
      </button>
    </form>

    <form @submit.prevent="addRoomUser" v-if="inviteRoomId">
      <select v-model="invitedUsername">
        <option default value="">Select User</option>
        <option v-for="user in users" :key="user.id" :value="user.id">
          {{ user.first_name }}
        </option>
      </select>
      <button type="submit" :disabled="disableForm || !invitedUsername">
        Add User
      </button>
      <button class="button-cancel" @click="inviteRoomId = null">
        Cancel
      </button>
    </form>

        <form @submit.prevent="deleteRoomUser" v-if="removeRoomId">
      <select v-model="removeUserId">
        <option default value="">Select User</option>
        <option v-for="user in removeUsers" :key="user.id" :value="user.id">
          {{ user.first_name }}
        </option>
      </select>
      <button type="submit" :disabled="disableForm || !removeUserId">
        Remove User
      </button>
      <button class="button-cancel" @click="removeRoomId = null">
        Cancel
      </button>
    </form>-->

  <chat-window
    :current-user-id="currentUserId"
    :rooms="loadedRooms"
    :messages="messages"
    :rooms-loaded="roomsLoaded"
    
    :room-id="roomId"
    :messages-loaded="messagesLoaded"
    @add-room="proverka"
    @send-message="sendMessage($event)"
    @fetch-messages="fetchMessages"
    @room-action-handler="menuActionHandler"
    
    
  />
  </div>
 
</template>

<script>
  import ChatWindow from 'vue-advanced-chat'
  import 'vue-advanced-chat/dist/vue-advanced-chat.css'
  import moment from 'moment'
  import axios from 'axios' 
  import $ from 'jquery'

  export default {

    components: {
      ChatWindow
    },
    props:{
      myrooms: Array,
      test: String,
      mymessages : Array,
    },

    data() {
      return {
        //(new Date().getDay())+' '+(new Date().toLocaleString('en-us', { month: 'long' })),
        online : 0,
        groups : 0,
        removeRoomId: null,
        users: [],
        removeUsers: [],
        inviteRoomId: null,
        removeUserId: '',
        addNewRoom: false,
        invitedUsername: '',
        currentUserId: this.$page.props.auth.user.id,
        rooms: [],
        roomId: '12',
       /* roomActions  : [
          { name: 'inviteUser', title: 'Пригласить' },
          { name: 'removeUser', title: 'Удалить участника' },
          { name: 'deleteRoom', title: 'Удалить комнату' }
        ],*/
        messages : [],
        roomsLoaded: false,
        messagesLoaded: false,
        addRoomUsername: '',
        disableForm: false,
      }
    },
    computed:{

      loadedRooms() {
         const r = {room: this.roomId }
         axios.post('/chat',r)
        .then(response => {
          var messages = [];
          const roomId = this.roomId;
          this.online = response.data.online;
          this.rooms = response.data.myrooms;
          this.groups = response.data.groups;
          this.users = response.data.myusers;
          response.data.mymessages.forEach(function addMessage(element){
            let date = new Date(element.created_at);
            var h = date.getHours();
            var m = date.getMinutes();

            h = (h<10) ? '0' + h : h;
            m = (m<10) ? '0' + m : m;
           
            var message = {
              _id: element.id,
              roomId: element.room_id,
              content: element.content,
              senderId: element.senderId,
              username: element.sender.first_name,
              avatar: '/storage'+element.sender.photo_path,
              date: (new Date().getDay())+' '+(new Date().toLocaleString('en-us', { month: 'long' })),
              timestamp: h+':'+m,
              system: false,
              saved: true,
              distributed: true,
              seen: element.seen,
              deleted: false,
              disableActions: false,
              disableReactions: false,
            };
            if(message.senderId == roomId){
              messages.push(message);
            }else if(message.roomId == roomId){
               messages.push(message);
            }
          });

          this.messages = messages;
                   
          this.roomsLoaded = true;
          this.messagesLoaded = true;
         // console.log(rooms[0].messages[0].content);  
         //this.messages.push(rooms[0].messages); 
         
        });

        return this.rooms.slice(0, this.roomsLoadedCount = this.rooms.length)
      },
    },

    methods:{
      proverka(){
        console.log(this.online);
        this.addNewRoom = true;
      },
        menuActionHandler({ action, roomId }) {
        
          
        switch (action.name) {
          case 'inviteUser':
            return this.inviteUser(roomId)
          case 'removeUser':
            return this.removeUser(roomId)
          case 'deleteRoom':
            return this.deleteRoom(roomId)
        }
      },
      inviteUser(roomId) {
        this.inviteRoomId = roomId
      },
      fetchMessages({ room, options = {} }) {
          this.messagesLoaded = false
          this.roomId = room.roomId;
        // use timeout to imitate async server fetched data
        /*setTimeout(() => {
          var messages = [];
         axios.get('/chat')
        .then(response => {
           
          
          
          response.data.mymessages.forEach(function addMessage(element){
            var message = {
              _id: element.id,
              roomId: element.room_id,
              content: element.content,
              senderId: element.senderId,
              username: element.username,
              avatar: '/storage'+element.sender.photo_path,
              date: (new Date().getDay())+' '+(new Date().toLocaleString('en-us', { month: 'long' })),
              timestamp: '10:20',
              system: false,
              saved: true,
              distributed: true,
              seen: element.seen,
              deleted: false,
              disableActions: false,
              disableReactions: false,
            };
            if(message.roomId == room.roomId){
              
              messages.push(message);
            }
          });

          this.messages = messages;
          
         // console.log(rooms[0].messages[0].content);  
         //this.messages.push(rooms[0].messages); 
         
        });
          this.messagesLoaded = true
        })*/
      },
      resetRooms() {
        this.loadingRooms = true
        this.loadingLastMessageByRoom = 0
        this.roomsLoadedCount = 0
        this.rooms = []
        this.roomsLoaded = true
        this.startRooms = null
        this.endRooms = null
        this.roomsListeners.forEach(listener => listener())
        this.roomsListeners = []
        this.resetMessages()
      },
      sendMessage($event){
                //this.messages.push($event);

        let data = new FormData();
        var file = null;
        if($event.file){
          
          file = {
            name: $event.file.name,
            size: $event.file.size,
            type: $event.file.type,
            extension: $event.file.extension || $event.file.type,
            url: $event.file.localUrl
          }
          var imagefile = document.querySelector($event.file.localUrl);
          data.append('file', imagefile.files[0]);
        }

        data.append('room_id', this.roomId);
        data.append('content',$event.content);
        data.append('seen', 1);
        data.append('senderId',this.$page.props.auth.user.id);
        
        // room_id : this.roomId,//this.roomId,
          //'date' : '2021-07-14',
          //content : $event.content,
          //seen : 1,
          //senderId : this.$page.props.auth.user.id,
          //file: file,

        let config = {
          header : {
            'Content-Type' : 'multipart/form-data'
          }
        }

        axios.post('/send-message', data)
        .then(response => {
          var messages = [];
          messages.push(response.data.message);
          console.log(response.data.message);
          this.messsages = messages;
          this.messagesLoaded = true;
        });
      },
          createRoom() {
    
        this.disableForm = false
        axios.put('/createroom',{
          name: this.addRoomUsername,
          avatar: '/img/logo.svg',
          count: 0
        })
        .then(response => {
          this.rooms.push(response.data);
          //console.log(response.data.room_name);
          //this.addNewRoom = true;
          //this.addRoomUsername = response.data.room_name;
          //this.fetchRooms();
        });
        
      },
      addRoomUser(){

        axios.put('/add-room-user',{
            room_id : this.inviteRoomId,//this.roomId,
            //'date' : '2021-07-14',
            user_id : this.invitedUsername,
           // timestamp : + Date.now(),
        })
        .then(response => {
          this.inviteRoomId = null;
          alert(response.data);
        });
      },
      removeUser(roomId) {
      
        this.removeRoomId = roomId
        this.removeUsers = this.rooms.find(room => room.roomId === roomId).myusers
      
      },
      deleteRoomUser(){
       
         axios.put('/delete-room-user',{
            room_id : this.removeRoomId,//this.roomId,
            //'date' : '2021-07-14',
            user_id : this.removeUserId,
           // timestamp : + Date.now(),
        })
        .then(response => {
          this.removeRoomId = null;
          alert(response.data);
        });
      },
      deleteRoom(roomId){
        axios.put('/delete-room',{
            room_id : roomId,//this.roomId,
            //'date' : '2021-07-14',
        })
        .then(response => {
          alert(response.data);
        });
      },
      connect(){
       
                
        
      }
    },

  }
</script>