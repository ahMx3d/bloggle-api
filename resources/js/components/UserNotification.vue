<template>
    <li class="shopcart">
        <a class="cartbox_active" href="javascript:void(0);">
            <span class="product_qun" v-if="unreadCount>0">
                {{ unreadCount }}
            </span>
        </a>
        <!-- Start Shopping Cart -->
        <div class="block-minicart minicart__active">
            <div class="minicart-content-wrapper" v-if="unreadCount>0">
                <div class="single__items">
                    <div class="miniproduct">

                        <!-- this div may have class of mt--20 -->
                        <div
                            class="item01 d-flex mt--20"
                            v-for="item in unread"
                            :key="item.id">
                            <div class="thumb">
                                <a
                                    :href="`${item.data.post_slug}`"
                                    @click="readNotification(item)">
                                    <!-- :href="`edit-comments/${item.data.id}`" -->
                                    <img
                                        src="/frontend/images/icons/comment.png"
                                        alt="`${item.data.post_title}`" />
                                </a>
                            </div>
                            <div class="content">
                                <!-- h6 tag may need to be cutted -->
                                <h6>
                                    <a
                                        :href="`${item.data.post_slug}`"
                                        @click="readNotification(item)">
                                        <!-- :href="`edit-comments/${item.data.id}`" -->
                                        You have a new comment on
                                        <span class="prize">{{ item.data.post_title }}</span>
                                    </a>
                                </h6>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Shopping Cart -->
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
            // user id from head meta element value
            let userId = $('meta[name="user-id"]').attr('content');
            if (userId) {
                this.getNotifications();
                // broadcast channel route from routes/channels.php
                Echo.private(
                    `App.User.${userId}`
                ).notification(notification=>{
                    this.unread.unshift(notification);
                    this.unreadCount++;
                });
            }
        },
        methods: {
            getNotifications(){
                axios.get(
                    'user/notifications/get'
                ).then(res=>{
                    this.read        = res.data.read;
                    this.unread      = res.data.unread;
                    this.unreadCount = res.data.unread.length;
                }).catch(error=>Exception.handle(error));
            },
            readNotification(notification){
                axios.post(
                    'user/notifications/read',
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
