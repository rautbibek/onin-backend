<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-data-table
                :headers="headers"
                :items="cities.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'City per page',
                    'show-current-page': true,
                    'show-first-last-page': true
                }"
                @update:options="getCities"
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
                                @click:append="getCities"
                                @blur="getCities"
                            ></v-text-field>
                        </v-col>
                        <v-btn
                            class="ma-2"
                            @click="addCity"
                            fab
                            x-small
                            color="success"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                        <v-btn
                            @click="getCities($options)"
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
                <template v-slot:item.district_id="{ item }">
                    <span>{{ item.district_name }}</span>
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
                            v-model="formData.district_id"
                            :items="discrict"
                            item-text="name"
                            item-value="id"
                            label="Select district *"
                            :rules="[required('District')]"
                            outlined
                        >
                        </v-autocomplete>
                        <v-text-field
                            v-model="formData.name"
                            :rules="[required('City Name')]"
                            label="City Name"
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
                        @click="saveCity"
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
    name: "City",
    mixins: [commonMixin, validation, addressMixin],
    components: {
        ConfirmationBox
    },
    data: () => ({
        search_keyword: "",
        title: "Cities",
        confirm: false,
        dialog: false,
        loading: false,
        valid: true,
        city_id: "",
        buttonLoading: false,
        meta: [],
        cities: [],
        formTitle: "City",
        formData: {},
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "City",
                disabled: true,
                to: "/city"
            }
        ],
        headers: [
            {
                text: "ID",
                align: "start",
                value: "id",
                sortable: false
            },
            { text: "District", value: "district_id" },

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
        getCities(e) {
            this.loading = true;
            axios
                .get(`/api/city?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.cities = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        edit(item) {
            this.formData.id = item.id;
            this.formData.district_id = item.district_id;
            this.formData.name = item.name;
            this.formData.delivery_charge = item.delivery_charge;
            console.log(this.formData);
            this.dialog = true;
        },
        saveCity() {
            if (this.$refs.form.validate()) {
                this.buttonLoading = true;
                axios
                    .post(`/api/city`, this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.getCities(this.$options);
                        this.dialog = false;
                        this.city_id = "";

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
        addCity() {
            this.dialog = true;
        },
        cancel() {
            this.city_id = "";
            this.confirm = false;
        },
        closeModel() {
            this.$refs.form.reset();
            this.$refs.form.resetValidation();
            this.dialog = false;
        },
        confirmation(item) {
            this.confirm = true;
            this.city_id = item.id;
            //this.deleteItem(item);
        },
        deleteItem() {
            axios
                .delete(`/api/city/${this.city_id}`)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.confirm = false;
                    this.city_id = "";
                    this.getCities(this.$options);
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        }
    },

    created() {
        this.getAllDistrict();
    }
};
</script>
