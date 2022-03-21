<template>
    <div>
        <v-menu
            v-model="menu"
            close-on-content-click
            :nudge-width="200"
            offset-y
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon large dark v-bind="attrs" v-on="on">
                    <v-avatar size="32px" item>
                        <v-img src="/src/logo.png" alt="Vuetify"></v-img
                    ></v-avatar>
                </v-btn>
            </template>

            <v-card>
                <v-list>
                    <v-list-item>
                        <v-list-item-avatar>
                            <img src="/src/user.png" alt="John" />
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title>{{
                                $user.name
                            }}</v-list-item-title>
                            <v-list-item-subtitle>Admin</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>

                <v-divider></v-divider>

                <v-list>
                    <v-list-item>
                        <v-list-item-action>
                            <v-switch
                                @change="$emit('test', !mode)"
                                :value="mode"
                                color="primary"
                            ></v-switch>
                        </v-list-item-action>
                        <v-list-item-title>Dark Mode</v-list-item-title>
                    </v-list-item>
                    <v-list-item link>
                        <v-list-item-icon
                            ><v-icon>mdi-lock</v-icon></v-list-item-icon
                        >
                        <v-list-item-title>Change Password</v-list-item-title>
                    </v-list-item>

                    <v-list-item @click="logout">
                        <v-list-item-icon
                            ><v-icon>mdi-logout</v-icon></v-list-item-icon
                        >
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-menu>
    </div>
</template>

<script>
export default {
    props: {
        mode: {
            type: Boolean,
            default: false
        }
    },

    name: "ProfileImage",
    data() {
        return {
            menu: false
        };
    },
    methods: {
        changeMode(event) {
            this.$emit("changeMode", event.target.value);
        },
        logout() {
            axios
                .post("/logout")
                .then(res => {
                    console.log(res.data);
                    window.location.href = "/";
                })
                .catch(err => {
                    console.log(err.response.data.errors);
                });
        }
    }
};
</script>

<style lang="scss" scoped></style>
