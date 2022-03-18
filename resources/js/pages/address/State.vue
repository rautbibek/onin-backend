<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-toolbar flat dense extended>
                <v-toolbar-title>{{ title }} </v-toolbar-title>

                <v-spacer></v-spacer>

                <v-col cols="4" class="mt-5">
                    <v-text-field
                        v-model="search_keyword"
                        class="mt-5"
                        label="search"
                        outlined
                        dense
                        append-icon="search"
                        placeholder="Start typing..."
                        @click:append="getState"
                        @blur="getState"
                    ></v-text-field>
                </v-col>
                <v-btn
                    class="ma-2 mt-5"
                    @click="addState"
                    fab
                    x-small
                    color="success"
                >
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
                <v-btn
                    class="mt-3"
                    @click="getState"
                    fab
                    x-small
                    color="primary"
                >
                    <v-icon>mdi-refresh</v-icon>
                </v-btn>
            </v-toolbar>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left">
                                ID
                            </th>
                            <th class="text-left">
                                Name
                            </th>
                            <th class="text-left">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in states" :key="item.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.name }}</td>
                            <td>
                                <v-btn
                                    x-small
                                    fab
                                    color="primary"
                                    dark
                                    @click="edit(item)"
                                >
                                    <v-icon dark>
                                        mdi-pencil
                                    </v-icon>
                                </v-btn>
                                <v-btn
                                    @click="confirmation(item)"
                                    x-small
                                    fab
                                    color="error"
                                    dark
                                >
                                    <v-icon dark>
                                        mdi-delete
                                    </v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">{{ title }}</span>
                    <v-spacer></v-spacer>
                    <v-icon left @click="closeModel">close</v-icon>
                </v-card-title>

                <v-card-text>
                    <ValidationErrors :errors="errors"></ValidationErrors>
                    <v-container> </v-container>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            v-model="formData.name"
                            :rules="[required('State Name')]"
                            label="State Name"
                            outlined
                        ></v-text-field>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="closeModel" color="error">
                        <v-icon left>mdi-cancel</v-icon>
                        Cancel
                    </v-btn>
                    <v-btn
                        color="success"
                        @click="saveState"
                        :loading="buttonLoading"
                    >
                        <v-icon left>save</v-icon>
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <ConfirmationBox :confirm="confirm">
            <template v-slot:cancel>
                <v-btn small color="error" dark @click="cancel">
                    <v-icon left>mdi-cancel</v-icon>
                    no
                </v-btn>
            </template>
            <template v-slot:ok>
                <div>
                    <v-btn
                        small
                        color="green darken-1"
                        dark
                        @click="deleteItem"
                    >
                        <v-icon left>mdi-check-circle</v-icon>
                        yes
                    </v-btn>
                </div>
            </template>
        </ConfirmationBox>
    </div>
</template>
<script>
import axios from "axios";
import ConfirmationBox from "../../components/ConfirmationBox.vue";
import { commonMixin } from "../../mixins/commonMixin";
import { validation } from "../../mixins/validationMixin";
export default {
    name: "State",
    mixins: [commonMixin, validation],
    components: {
        ConfirmationBox
    },
    data: () => ({
        search_keyword: "",
        title: "States",
        confirm: false,
        dialog: false,
        loading: false,
        valid: true,
        state_id: "",
        buttonLoading: false,
        meta: [],
        states: [],
        formTitle: "States",
        formData: {
            id: "",
            name: ""
        },
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "State",
                disabled: true,
                to: "/state"
            }
        ]
    }),

    methods: {
        getState() {
            this.loading = true;
            axios
                .get("/api/state", {
                    params: {
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.states = res.data.data;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        edit(item) {
            this.formData.id = item.id;
            this.formData.name = item.name;
            this.dialog = true;
        },
        saveState() {
            if (this.$refs.form.validate()) {
                this.buttonLoading = true;
                axios
                    .post(`/api/state`, this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.getState();
                        this.dialog = false;
                        this.formData.id = "";
                        this.formData.name = "";
                        this.buttonLoading = false;
                        this.errors = [];
                    })
                    .catch(error => {
                        this.$toast.error(error.response.data.message, {
                            timeout: 2000
                        });
                        this.errors = error.response.data.errors;
                        this.buttonLoading = false;
                    });
            }
        },
        addState() {
            this.formData.id = "";
            this.formData.name = "";
            this.$refs.form.resetValidation();
            this.dialog = true;
        },
        cancel() {
            this.state_id = "";
            this.confirm = false;
        },
        closeModel() {
            this.formData.id = "";
            this.formData.name = "";
            this.dialog = false;
        },
        confirmation(item) {
            this.confirm = true;
            this.state_id = item.id;
            //this.deleteItem(item);
        },
        deleteItem() {
            axios
                .delete(`/api/state/${this.state_id}`)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.confirm = false;
                    this.state_id = "";
                    this.getState();
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        }
    },

    created() {
        this.getState();
    }
};
</script>
