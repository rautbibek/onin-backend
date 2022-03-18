<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-data-table
                :headers="headers"
                :items="local_areas.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'Local Area per page',
                    'show-current-page': true,
                    'show-first-last-page': true
                }"
                @update:options="getLocalAreas"
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
                                @click:append="getLocalAreas"
                                @blur="getLocalAreas"
                            ></v-text-field>
                        </v-col>
                        <v-btn
                            class="ma-2"
                            @click="addLocalArea"
                            fab
                            x-small
                            color="success"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                        <v-btn
                            @click="getLocalAreas($options)"
                            fab
                            x-small
                            color="primary"
                        >
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </v-toolbar>
                </template>
                <template v-slot:item.id="{ index }">
                    <span>{{ index + meta.from }}</span>
                </template>
                <template v-slot:item.city_id="{ item }">
                    <span>{{ item.city_name }}</span>
                </template>

                <template v-slot:item.delivery_charge="{ item }">
                    <span>Rs .{{ item.delivery_charge }}</span>
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

                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-autocomplete
                            v-model="formData.city_id"
                            :items="city"
                            item-text="name"
                            item-value="id"
                            label="Select city *"
                            :rules="[required('City')]"
                            outlined
                        >
                        </v-autocomplete>
                        <v-text-field
                            v-model="formData.name"
                            :rules="[required('Local Area Name')]"
                            label="Local Area Name"
                            outlined
                        ></v-text-field>
                        <v-text-field
                            type="number"
                            v-model.number="formData.delivery_charge"
                            label="Delivery Cost"
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
                        @click="saveLocalArea"
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
    name: "LocalAreas",
    mixins: [commonMixin, validation, addressMixin],
    components: {
        ConfirmationBox
    },
    data: () => ({
        search_keyword: "",
        title: "Local Areas",
        confirm: false,
        dialog: false,
        loading: false,
        valid: true,
        local_area_id: "",
        buttonLoading: false,
        meta: [],

        local_areas: [],
        formTitle: "Local Areas",
        formData: {},
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "Local area",
                disabled: true,
                to: "/local-area"
            }
        ],
        headers: [
            {
                text: "ID",
                align: "start",
                value: "id",
                sortable: false
            },
            { text: "City", value: "city_id" },

            { text: "Name", value: "name", sortable: true },
            {
                text: "Delivery Charge",
                value: "delivery_charge",
                sortable: false
            },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ]
    }),

    methods: {
        getLocalAreas(e) {
            this.loading = true;
            axios
                .get(`/api/localarea?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.local_areas = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        edit(item) {
            this.formData.id = item.id;
            this.formData.city_id = item.city_id;
            this.formData.name = item.name;
            this.formData.delivery_charge = item.delivery_charge;
            this.dialog = true;
        },
        saveLocalArea() {
            if (this.$refs.form.validate()) {
                this.buttonLoading = true;
                axios
                    .post(`/api/localarea`, this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.getLocalAreas(this.$options);
                        this.dialog = false;
                        this.local_area_id = "";

                        this.buttonLoading = false;
                        this.errors = [];
                        this.$refs.form.reset();
                        this.$refs.form.resetValidation();
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
        addLocalArea() {
            this.dialog = true;
        },
        cancel() {
            this.local_area_id = "";
            this.confirm = false;
        },
        closeModel() {
            this.formData = {};
            this.$refs.form.reset();
            this.$refs.form.resetValidation();
            this.dialog = false;
        },
        confirmation(item) {
            this.confirm = true;
            this.local_area_id = item.id;
            //this.deleteItem(item);
        },
        deleteItem() {
            axios
                .delete(`/api/localarea/${this.local_area_id}`)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.confirm = false;
                    this.local_area_id = "";
                    this.getLocalAreas(this.$options);
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        }
    },

    created() {
        this.getAllCity();
    }
};
</script>
