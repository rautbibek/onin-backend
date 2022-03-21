<template>
    <div>
        <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
                <v-btn class="ma-2" icon v-bind="attrs" v-on="on">
                    <v-badge
                        color="green"
                        :content="notifications.length"
                        overlap
                    >
                        <v-icon>mdi-bell</v-icon>
                    </v-badge>
                </v-btn>
            </template>
            <v-card width="400" height="500" class="mx-auto">
                <v-list subheader three-line>
                    <v-subheader>Notifications</v-subheader>
                    <hr />
                    <v-list-item
                        style="background:#f5f5f5; cursor:pointer"
                        color="red"
                        class="my-2"
                        v-for="(notification, index) in notifications"
                        ripple
                        :key="index"
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
                    <hr />
                    <v-subheader class="text-center"
                        >Clear all notification</v-subheader
                    >
                </v-list>
            </v-card>
        </v-menu>
    </div>
</template>

<script>
export default {
    name: "Notification",
    data() {
        return {
            notifications: [],
            files: [
                {
                    color: "blue",
                    icon: "mdi-clipboard-text",
                    subtitle: "Jan 20, 2014",
                    title: "Vacation itinerary"
                },
                {
                    color: "amber",
                    icon: "mdi-gesture-tap-button",
                    subtitle: "Jan 10, 2014",
                    title: "Kitchen remodel"
                }
            ],
            folders: [
                {
                    subtitle: "Jan 9, 2014",
                    title: "Photos"
                },
                {
                    subtitle: "Jan 17, 2014",
                    title: "Recipes"
                },
                {
                    subtitle: "Jan 28, 2014",
                    title: "Work"
                }
            ]
        };
    },
    methods: {
        getNotification() {
            axios
                .get("/api/unread/notification")
                .then(res => {
                    this.notifications = res.data;
                    console.log(this.notifications);
                })
                .catch(error => {
                    console.log("errors");
                });
        }
    },
    created() {
        this.getNotification();
    }
};
</script>

<style lang="scss" scoped></style>
