<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-data-table
                :headers="headers"
                :items="districts.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'District per page',
                    'show-current-page': true,
                    'show-first-last-page': true
                }"
                @update:options="getDIstricts"
            >
                <template v-slot:top>
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
                                @click:append="getDIstricts"
                                @blur="getDIstricts"
                            ></v-text-field>
                        </v-col>
                        <v-btn
                            class="ma-2"
                            @click="addDistrict"
                            fab
                            x-small
                            color="success"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                        <v-btn fab x-small color="primary">
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </v-toolbar>
                </template>
                <template v-slot:item.id="{ index }">
                    <span>{{ index + meta.from }}</span>
                </template>
                <template v-slot:item.state_id="{ item }">
                    <span>{{ item.state_name }}</span>
                </template>

                <template v-slot:item.action="{ item }">
                    <v-btn x-small fab color="primary" dark @click="edit(item)">
                        <v-icon dark>
                            mdi-pencil
                        </v-icon>
                    </v-btn>

                    <v-btn
                        x-small
                        fab
                        color="error"
                        dark
                        @click="confirmation(item)"
                    >
                        <v-icon dark>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
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
                    <v-autocomplete
                        v-model="formData.state_id"
                        :items="state"
                        item-text="name"
                        item-value="id"
                        label="Select State *"
                        :rules="[required('State')]"
                        outlined
                    >
                    </v-autocomplete>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            v-model="formData.name"
                            :rules="[required('District Name')]"
                            label="District Name"
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
                        @click="saveDistrict"
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
import { addressMixin } from "../../mixins/addressMixin";
import { validation } from "../../mixins/validationMixin";
export default {
    name: "District",
    mixins: [commonMixin, validation, addressMixin],
    components: {
        ConfirmationBox
    },
    data: () => ({
        search_keyword: "",
        title: "DIstricts",
        confirm: false,
        dialog: false,
        loading: false,
        valid: true,
        district_id: "",
        buttonLoading: false,
        meta: [],
        districts: [],
        formTitle: "District",
        formData: {},
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
        ],
        headers: [
            {
                text: "ID",
                align: "start",
                value: "id",
                sortable: false
            },
            { text: "state", value: "state_id" },

            { text: "Name", value: "name", sortable: true },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ]
    }),

    methods: {
        getDIstricts(e) {
            this.loading = true;
            axios
                .get(`/api/district?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.districts = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        edit(item) {
            this.formData.id = item.id;
            this.formData.state_id = item.state_id;
            this.formData.name = item.name;
            this.dialog = true;
        },
        saveDistrict() {
            if (this.$refs.form.validate()) {
                this.buttonLoading = true;
                axios
                    .post(`/api/district`, this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.getDIstricts(this.$options);
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
        addDistrict() {
            this.formData.id = "";
            this.formData.name = "";
            //this.$refs.form.resetValidation();
            this.dialog = true;
        },
        cancel() {
            this.district_id = "";
            this.confirm = false;
        },
        closeModel() {
            this.formData.id = "";
            this.formData.name = "";
            this.dialog = false;
        },
        confirmation(item) {
            this.confirm = true;
            this.district_id = item.id;
            //this.deleteItem(item);
        },
        deleteItem() {
            axios
                .delete(`/api/district/${this.district_id}`)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.confirm = false;
                    this.district_id = "";
                    this.getDIstricts(this.$options);
                })
                .catch(error => {
                    this.confirm = false;
                    this.district_id = "";
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        }
    },

    created() {
        this.getAllStates();
    }
};
</script>
