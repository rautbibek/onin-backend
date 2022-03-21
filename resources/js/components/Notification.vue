<template>
    <div>
        <v-menu offset-y closeOnClick>
            <template v-slot:activator="{ on, attrs }">
                <v-btn class="ma-2" icon v-bind="attrs" v-on="on">
                    <v-badge
                        color="green"
                        :content="notifications.length"
                        :value="notifications.length ? true : false"
                        overlap
                    >
                        <v-icon>mdi-bell</v-icon>
                    </v-badge>
                </v-btn>
            </template>
            <v-card v-if="notifications.length > 0" width="400" class="mx-auto">
                <v-list subheader three-line>
                    <v-subheader>Notifications</v-subheader>

                    <div
                        style="max-height: 363px;
                            min-height: 213px;
                            overflow-y: auto;
                            border-top:1px solid #ddd; border-bottom:1px solid #ddd"
                    >
                        <v-list-item
                            style="background:#f5f5f5; cursor:pointer"
                            color="red"
                            class="my-2"
                            v-for="(notification, index) in notifications"
                            ripple
                            :key="index"
                            @click="orderDetail(notification)"
                        >
                            <v-list-item-avatar>
                                <v-icon small class="green lighten-1" dark>
                                    mdi-bell
                                </v-icon>
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-title>{{
                                    notification.data.title
                                }}</v-list-item-title>
                                <v-list-item-subtitle>{{
                                    notification.data.message
                                }}</v-list-item-subtitle>
                                <small class="text-right">{{
                                    notification.created_at
                                        | moment("calendar", "July 10 2011")
                                }}</small>
                            </v-list-item-content>

                            <!-- <v-list-item-action>
                            <v-btn icon>
                                <v-icon color="red lighten-1"
                                    >mdi-information</v-icon
                                >
                            </v-btn>
                        </v-list-item-action> -->
                        </v-list-item>
                    </div>

                    <v-subheader class="text-center"
                        >Clear all notification</v-subheader
                    >
                </v-list>
            </v-card>
            <v-card v-else class="text-center">
                <v-subheader>Notifications</v-subheader>
                <hr />
                <div
                    style="display: flex;
                            height: 200px;
                            width: 400px;
                            justify-content: center;
                            align-items: center;"
                >
                    <strong>Empty Notification</strong>
                </div></v-card
            >
        </v-menu>
    </div>
</template>

<script>
export default {
    name: "Notification",
    data() {
        return {
            notifications: []
        };
    },
    methods: {
        getNotification() {
            axios
                .get("/api/unread/notification")
                .then(res => {
                    this.notifications = res.data;
                    //console.log(this.notifications);
                })
                .catch(error => {
                    console.log("errors");
                });
        },
        orderDetail(data) {
            axios
                .post(`/api/read/notification/${data.id}`)
                .then(() => {
                    this.$router.push({
                        name: "OrderDetail",
                        params: { id: data.data.order.id }
                    });
                    this.getNotification();
                })
                .catch(error => {
                    console.log("error");
                });
        }
    },
    created() {
        this.getNotification();
    }
};
</script>

<style lang="scss" scoped></style>
