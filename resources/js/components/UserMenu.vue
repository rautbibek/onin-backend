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
                    <v-list-item @click="changePasswordDialog = true">
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
        <v-dialog v-model="changePasswordDialog" persistent max-width="490">
            <v-card>
                <v-card-title class="text-h5">
                    Change Password
                </v-card-title>
                <hr />
                <v-card-text>
                    <v-form ref="form" lazy-validation v-model="valid">
                        <v-text-field
                            v-model="formData.old_password"
                            :append-icon="
                                showOldPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showOldPassword ? 'text' : 'password'"
                            name="input-10-1"
                            label="Old Password"
                            hint="At least 8 characters"
                            :rules="[rules.required, rules.min]"
                            counter
                            dense
                            outlined
                            @click:append="showOldPassword = !showOldPassword"
                        ></v-text-field>
                        <v-text-field
                            v-model="formData.password"
                            :append-icon="
                                showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showPassword ? 'text' : 'password'"
                            name="input-10-1"
                            :rules="[rules.required, rules.min]"
                            label="New Password"
                            hint="At least 8 characters"
                            counter
                            dense
                            outlined
                            @click:append="showPassword = !showPassword"
                        ></v-text-field>
                        <v-text-field
                            v-model="formData.confirm_password"
                            :append-icon="
                                showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showConfirmPassword ? 'text' : 'password'"
                            :rules="[rules.required, rules.min]"
                            name="input-10-1"
                            label="Old Password"
                            hint="At least 8 characters"
                            counter
                            outlined
                            dense
                            @click:append="
                                showConfirmPassword = !showConfirmPassword
                            "
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <hr />
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="changePasswordDialog = false"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="changePasswordDialog = false"
                    >
                        UPDATE PASSWORD
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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

    name: "UserMenu",
    data() {
        return {
            valid: false,
            menu: false,
            showPassword: false,
            showOldPassword: false,
            showConfirmPassword: false,
            formData: {},
            changePasswordDialog: false,
            rules: {
                required: value => !!value || "Required.",
                min: v => v.length >= 8 || "Min 8 characters",
                emailMatch: () =>
                    `The email and password you entered don't match`
            }
        };
    },
    methods: {
        changeMode(event) {
            this.$emit("changeMode", event.target.value);
        },
        changePassword() {},
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
