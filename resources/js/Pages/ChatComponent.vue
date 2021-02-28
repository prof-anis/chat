<template>
    <div>
        <section class="col-lg-12 col-md-12  connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <!-- /.card -->

            <!-- DIRECT CHAT -->
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header">
                    <h3 class="card-title">Direct Chat</h3>

                    <div class="card-tools">
                        <span title="3 New Messages" class="badge badge-primary">3</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                            <i class="fas fa-comments"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Conversations are loaded here -->
                    <div class="direct-chat-messages">

                        <div v-for="chat in chats">
                            <!-- Message. Default to the left -->
                            <div v-if="chat.sender.id != user.id" class="direct-chat-msg">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">{{ chat.sender.name }}</span>
                                    <span class="direct-chat-timestamp float-right">{{ chat.created_at }}</span>
                                </div>
                                <!-- /.direct-chat-infos -->
                                <img class="direct-chat-img" :src="chat.sender.image" alt="message user image" width="128" height="128">
                                <!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    {{ chat.message }}
                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                            <!-- /.direct-chat-msg -->

                            <!-- Message to the right -->
                            <div v-else class="direct-chat-msg right">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-right">{{ chat.sender.name }}</span>
                                    <span class="direct-chat-timestamp float-left">{{ chat.created_at }}</span>
                                </div>
                                <!-- /.direct-chat-infos -->
                                <img class="direct-chat-img" :src="chat.sender.image"  height="128"  width="128" alt="message user image">
                                <!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    {{ chat.message }}
                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                            <!-- /.direct-chat-msg -->
                        </div>



                    </div>
                    <!--/.direct-chat-messages-->

                    <!-- Contacts are loaded here -->
                    <!-- /.direct-chat-pane -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <form @submit.prevent="sendMessage">
                        <div class="input-group">
                            <input @keyup="sendWhispers" v-model="message"  type="text" name="message" placeholder="Type Message ..." class="form-control">
                            <span class="input-group-append">
                      <button type="button" class="btn btn-primary" @click.prevent="sendMessage">Send</button>
                    </span>
                        </div>
                    </form>
                </div>
                <!-- /.card-footer-->
            </div>
            <!--/.direct-chat -->

            <!-- TO DO List -->
            <!-- /.card -->
        </section>
        <div class="col-md-6">
            <!-- USERS LIST -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Latest Members</h3>

                    <div class="card-tools">
                        <span class="badge badge-danger">{{ activeUsers.length }} New Members</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="users-list clearfix">
                        <li v-for="user in activeUsers">
                            <img :src="user.image" alt="User Image" width="200"  height="200">
                            <a class="users-list-name" href="#">{{ user.name }}</a>
                            <span class="users-list-date">Today</span>
                            <br>
                            <i style="color: green" v-if="user.is_typing">typing...</i>
                        </li>


                    </ul>
                    <!-- /.users-list -->
                </div>
                <!-- /.card-body -->

                <!-- /.card-footer -->
            </div>
            <!--/.card -->
        </div>

    </div>

</template>

<script lang="js">
export default {
    name:"ChatComponent",
    props:['user', 'chats'],
    data() {
        return {
            activeUsers:[
                this.user
            ],
            typingUser: '',
            message:''
        }
    },
    mounted() {
        Echo.join('chat-room')
            .joining(user =>  {
                this.activeUsers.push(user);
            })
            .here(users =>  {
                this.activeUsers = users;
            })
            .leaving(user => {
                this.activeUsers = this.activeUsers.filter(val => {
                    return val.id != user.id;
                })
            })
            .listen('MessageSent', info => {
             //   alert('new message');
                console.log(info);
                this.chats.unshift(info.chat);
            })
            .listenForWhisper('typing', info => {
                console.log('typing');
              //  alert('typing');
                console.log('typing');
                this.activeUsers.forEach((val, index) => {
                    if (val.email == info.email) {
                        console.log(val)
                        this.activeUsers[index].is_typing = true;
                        console.log(this.activeUsers[index]);
                    }
                });


            })

    },
    methods:{
        sendWhispers: function () {
            let email = this.user.email;
            console.log('sending whipsers');
            window.Echo.join('chat-room').whisper('typing', this.user);
        },
        sendMessage: function () {
            let data = {
                message:this.message,
                sender_id: this.user.id
            }
            axios.post("/chat", data)
                .then(info => {
                    console.log(info);
                    this.message = '';
                    this.chats.unshift(info.data.chat)

                })
                .catch(error => {
                    console.log(error.response);
                })
        }
    }

}
</script>
