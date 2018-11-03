<template>
  <main-layout>
    <div class="card mt-3">
      <div class="card-body">
        <div class="card-title">
          <h3>Chat App</h3>
          <h5 v-if="loged_in" >Welcome {{myData.name}}<div class="pull-right"><button v-on:click="logOut()" class="btn btn-danger">Logout</button></div></h5>
          <hr>
        </div>
        <div class="card-body d-flex" v-if="loged_in">
          <div class="users col-md-2">
            <ul>
              <li v-on:click="sendTo(user.id)" :id="'user_'+user.id" v-for="(user, index) in users" :key="index">
                <span class="user" v-bind:class="{ active: isActive == user.id }">{{user.name}}</span>
              </li>
            </ul>
          </div>
          <div class="messages col-md-10" v-if="to!=''">
            <div class="chat-history col-md-12" v-on:click="loadHistory()" v-if="to!=''&&!noHistory" >load chat history</div>
            <div class="message-container col-md-12 mb-1" v-for="(msg, index) in messages" :key="index">
              <div v-if="msg.user==myData.id" class="message message-sent" >
                <span :title=" msg.created_at">{{ msg.message }}</span>
              </div>
              <div v-else class="message message-recived">
                <span :title=" msg.created_at">{{ msg.message }}</span>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <form @submit.prevent="login" v-if="!loged_in">
          <div class="gorm-group pb-3 d-flex">
            <input type="text" placeholder="User Name" v-model="user" class="form-control mr-1">
            <button type="submit" class="btn btn-success">Login</button>  
          </div>
        </form>
        <form @submit.prevent="sendMessage" v-if="loged_in&&to!=''">
          <div class="gorm-group pb-3 d-flex">
            <input type="text" placeholder="Message" v-model="message" class="form-control mr-1">
            <button type="submit" class="btn btn-success" v-if="message!=''">Send</button>
          </div>
        </form>
      </div>
    </div>
  </main-layout>
</template>

<script>
  import MainLayout from '../layouts/Main.vue';
  import Config from '../config.js';
  import io from 'socket.io-client';
  import axios from 'axios';
  
  export default {
    data() {
        return {
            loged_in:false,
            isActive:"",
            noHistory:false,
            messagesPage:1,
            myData:{},
            user: '',
            users: [],
            message: '',
            to:'',
            messages: [],
            socket :{},
        }
    },
    beforeMount() {
      if(localStorage.token!=undefined)
      {
        this.loged_in=true;
        this.users=JSON.parse(localStorage.allUsers);
        this.myData=JSON.parse(localStorage.myData);
        this.socket=io(Config['socketLink'])
        this.socket.on(`new-message:${this.myData.id}`,(data)=>{
          if(this.to!=''&&this.to==data['message'].user)
          {
            this.messages.push(data['message'])
          }
          else
          {
            var elem =  this.$el.querySelector(`#user_${data['message'].user}`);
            elem.className += " newMessage";
            this.$toasted.global.my_app_done({
              message : "New Message"
            });
          }
            
        });
      }
      
    },
    
    methods: {
      scrollTo(to){
        if(to=="top")
        {
          var elem =  this.$el.querySelector(".messages");
          elem.scrollTop = -elem.clientHeight;
        }
        else if(to=="bottom")
        {
          var elem =  this.$el.querySelector(".messages");
          elem.scrollTop = elem.clientHeight;
        }
        
      },
      loadHistory(){
        this.messagesPage++;
        axios.get(`${Config['serverLink']}/api/message-history/${this.to}?page=${this.messagesPage}&token=${localStorage.token}`)
        .then((response)  =>  {
          let temp=response.data.data.reverse();
          temp=temp.concat(this.messages);
          this.messages=temp;
          if(response.data.last_page==this.messagesPage)
          {
            this.noHistory=true;
          }
          this.scrollTo("top");
        },(error)  =>  {
          this.$toasted.global.my_app_error({
              message : error.response.data.error
          });
        });
      },
      logOut(){
        this.loged_in=false;
        this.users=[];
        this.myData=undefined;
        localStorage.allUsers=undefined;
        localStorage.myData=undefined;
        localStorage.token=undefined;
        this.socket.close();
        this.$toasted.global.my_app_done({
          message : "Loged Out Successfuly"
        });
      },
      sendMessage(e) {
        e.preventDefault();
        let postData={to:this.to,message:this.message}
        axios.post(`${Config['serverLink']}/api/new-message/?token=${localStorage.token}`,postData)
        .then((response)=>{
          this.message="";
          this.messages.push(response.data.newMessage);
          this.scrollTo("bottom");
        },(error)=>{
          this.$toasted.global.my_app_error({
              message : error.response.data.errors.message[0]
          });
        })
      },
      sendTo: function(user_id){
        var elem =  this.$el.querySelector(`#user_${user_id}`);
        elem.className=""
        this.to=user_id;
        this.isActive = user_id;
        this.messagesPage=1;
        axios.get(`${Config['serverLink']}/api/message-history/${this.to}?page=${this.messagesPage}&token=${localStorage.token}`)
        .then((response)  =>  {
          this.messages=response.data.data.reverse();
          this.scrollTo("bottom");
        },(error)  =>  {
          this.$toasted.global.my_app_error({
              message : error.response.data.error
          });
        });
      },
      login(e) {
        e.preventDefault();
        axios.post(`${Config['serverLink']}/init/${this.user}`,{})
        .then((response)  =>  {
          localStorage.token=response.data.token;
          localStorage.allUsers=JSON.stringify(response.data.allUsers);
          localStorage.myData=JSON.stringify(response.data.myData);
          this.user="";
          this.users=response.data.allUsers;
          this.myData=response.data.myData;
          this.loged_in=true;
          this.$toasted.global.my_app_done({
              message : "Loged In Successfuly"
          });
        }, (error)  =>  {
          this.$toasted.global.my_app_error({
              message : error.response.data.error
          });
        });
      }
    },
    components: {
      MainLayout
    }
  }
</script>
