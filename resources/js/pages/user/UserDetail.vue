<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-card-title>{{ user.name }}</v-card-title>
            <v-card-text>
                <v-timeline align-top dense>
                    <v-timeline-item
                        v-for="address in user.address"
                        :key="address"
                        color="deep-purple lighten-1"
                        small
                    >
                        <div>
                            <div class="font-weight-normal">
                                <strong>{{ address.full_address }}</strong>
                            </div>
                            <!-- <div>{{ message.message }}</div> -->
                        </div>
                    </v-timeline-item>
                </v-timeline>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            messages: [
                {
                    from: "You",
                    message: `Sure, I'll see you later.`,
                    time: "10:42am",
                    color: "deep-purple lighten-1"
                },
                {
                    from: "John Doe",
                    message: "Yeah, sure. Does 1:00pm work?",
                    time: "10:37am",
                    color: "green"
                },
                {
                    from: "You",
                    message: "Did you still want to grab lunch today?",
                    time: "9:47am",
                    color: "deep-purple lighten-1"
                }
            ],
            user: {},
            breadcrumb: [
                {
                    text: "Dashboard",
                    disabled: false,
                    to: "/dashboard"
                },
                {
                    text: "Users",
                    disabled: false,
                    to: "/users"
                },
                {
                    text: "User Detail",
                    disabled: true
                }
            ]
        };
    },
    methods: {
        getUser() {
            axios
                .get(`/api/user/${this.$route.params.id}`)
                .then(res => {
                    this.user = res.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    created() {
        this.getUser();
    }
};
</script>

<style lang="scss" scoped></style>
