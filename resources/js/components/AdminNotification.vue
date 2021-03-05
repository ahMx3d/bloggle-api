<template>
    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
        <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="alertsDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell fa-fw"></i>
            <!-- Counter - Alerts -->
            <span v-if="unreadCount>0" class="badge badge-danger badge-counter">
                {{ unreadCount }}
            </span>
        </a>
        <!-- Dropdown - Alerts -->
        <div v-if="unreadCount>0" class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="alertsDropdown">
            <h6 class="dropdown-header">
                Notifications Center
            </h6>
            <a  v-for="item in unread"
                :key="item.id"
                class="dropdown-item d-flex align-items-center"
                :href="`/admin/post_comments/${item.data.id}/edit`"
                @click="readNotification(item)">
                <div class="mr-3">
                    <div class="icon-circle bg-primary">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                </div>
                <div>
                    <!-- December 12, 2019 -->
                    <div class="small text-gray-500">{{ item.data.created_at }}</div>
                    <span class="font-weight-bold">
                        There is a new comment on {{ item.data.post_title }}!
                    </span>
                </div>
            </a>
        </div>
    </li>
</template>

<script>
    export default {
        data: function () {
            return {
                read       : {},
                unread     : {},
                unreadCount: 0
            }
        },
        created: function () {
            // admin id from head meta element value
            let adminId = $('meta[name="admin-id"]').attr('content');
            if (adminId) {
                this.getNotifications();
                // broadcast channel route from routes/channels.php
                Echo.private(
                    `App.User.${adminId}`
                ).notification(notification=>{
                    this.unread.unshift(notification);
                    this.unreadCount++;
                });
            }
        },
        methods: {
            getNotifications(){
                axios.get(
                    'notifications/get'
                ).then(res=>{
                    this.read        = res.data.read;
                    this.unread      = res.data.unread;
                    this.unreadCount = res.data.unread.length;
                }).catch(error=>Exception.handle(error));
            },
            readNotification(notification){
                axios.post(
                    'notifications/read',
                    {
                        id: notification.id
                    }
                ).then(res=>{
                    this.unread.splice(notification,1);
                    this.read.push(notification);
                    this.unreadCount--;
                });
                // .catch(error=>Exception.handle(error));
            }
        }
    }
</script>
