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
                        <v-alert
                            v-for="(error, index) in errors"
                            :key="index"
                            border="top"
                            color="red lighten-2"
                            dark
                        >
                            {{ error[0] }}
                        </v-alert>
                        <v-text-field
                            v-model="formData.password"
                            :append-icon="
                                showPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showPassword ? 'text' : 'password'"
                            name="input-10-1"
                            :rules="[
                                required('Password'),
                                minLength('Password', 8)
                            ]"
                            label="New Password"
                            hint="At least 8 characters"
                            counter
                            outlined
                            @click:append="showPassword = !showPassword"
                        ></v-text-field>
                        <v-text-field
                            v-model="formData.password_confirmation"
                            :append-icon="
                                showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            :type="showConfirmPassword ? 'text' : 'password'"
                            :rules="[
                                required('Password'),
                                minLength('Password', 8)
                            ]"
                            name="input-10-1"
                            label="Confirm Password"
                            hint="At least 8 characters"
                            counter
                            outlined
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
                        class="red text-light"
                        text
                        @click="
                            (changePasswordDialog = false),
                                (errors = []),
                                $refs.form.resetValidation(),
                                $refs.form.reset()
                        "
                    >
                        Cancel
                    </v-btn>
                    <v-btn class="primary" text @click="changePassword">
                        UPDATE
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import { validation } from "../mixins/validationMixin";
export default {
    props: {
        mode: {
            type: Boolean,
            default: false
        }
    },
    mixins: [validation],

    name: "UserMenu",
    data() {
        return {
            errors: [],
            valid: false,
            menu: false,
            showPassword: false,
            showOldPassword: false,
            showConfirmPassword: false,
            formData: {},
            changePasswordDialog: false,
            rules: {}
        };
    },
    methods: {
        changeMode(event) {
            this.$emit("changeMode", event.target.value);
        },
        changePassword() {
            if (this.$refs.form.validate()) {
                axios
                    .post("/change/password", this.formData)
                    .then(res => {
                        console.log(res);
                        this.changePasswordDialog = false;
                        this.errors = [];
                        this.$toast.success(res.data.message, {
                            timeout: 5000
                        });
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                        this.$toast.error(error.response.data.message, {
                            timeout: 5000
                        });
                    });
            }
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
