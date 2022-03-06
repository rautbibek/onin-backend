<template>
    <div>
        <!-- <b-link :items="breadcrumb"></b-link> -->
        <v-card>
            <v-data-table
                :headers="headers"
                :items="category_options.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'users per page',
                    'show-current-page': true,
                    'show-first-last-page': true
                }"
                @update:options="paginate"
            >
                <template v-slot:top>
                    <v-toolbar flat dense extended>
                        <v-toolbar-title>{{ title }} </v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>

                        <v-col cols="4" class="mt-5">
                            <v-text-field
                                v-model="search_keyword"
                                class="mt-5"
                                label="search"
                                clearable
                                outlined
                                dense
                                append-icon="search"
                                placeholder="Start typing..."
                                @click:append="paginate"
                                @blur="paginate"
                            ></v-text-field>
                        </v-col>
                        <v-btn
                            class="ma-2"
                            @click="addOption"
                            fab
                            x-small
                            color="success"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                        <v-btn @click="paginate" fab x-small color="primary">
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </v-toolbar>
                </template>
                <template v-slot:item.id="{ index }">
                    <span>{{ index + meta.from }}</span>
                </template>
                <template v-slot:item.created_at="{ item }">
                    <span>{{ item.created_at }}</span>
                </template>
                <template v-slot:item.values="{ item }">
                    <v-chip
                        v-for="(value, index) in item.values"
                        :key="index"
                        class="ma-2"
                        label
                        small
                        color="info"
                        dark
                    >
                        {{ value }}
                    </v-chip>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                x-small
                                fab
                                color="primary"
                                dark
                                v-bind="attrs"
                                @click="editOption(item)"
                                v-on="on"
                            >
                                <v-icon dark>
                                    mdi-pencil
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Edit </span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                x-small
                                fab
                                color="error"
                                dark
                                v-bind="attrs"
                                @click="confirmation(item)"
                                v-on="on"
                            >
                                <v-icon dark>
                                    mdi-delete
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Delete </span>
                    </v-tooltip>
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
                        <v-text-field
                            v-model="formData.name"
                            :rules="[required('Option Name')]"
                            label="Option Name"
                            required
                            outlined
                        ></v-text-field>

                        <v-combobox
                            outlined
                            v-model="formData.values"
                            :items="formData.values"
                            label="Options Values"
                            :rules="[combo('Values')]"
                            multiple
                            chips
                        ></v-combobox>
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
                        @click="saveOptions"
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
    name: "CategoryOptions",
    mixins: [commonMixin, validation],
    components: {
        ConfirmationBox
    },
    data: () => ({
        search_keyword: "",
        title: "Category Options",
        dialog: false,
        option_id: null,
        formData: {
            name: "",
            values: []
        },
        loading: false,
        confirm: false,
        category_options: [],
        meta: [],

        formTitle: "ColorFamilies",
        // breadcrumb: [
        //     {
        //         text: "Dashboard",
        //         disabled: false,
        //         href: "/dashboard"
        //     },
        //     {
        //         text: "Users",
        //         disabled: true,
        //         href: "/users"
        //     }
        // ],

        headers: [
            { text: "id", align: "start", value: "id", sortable: false },
            { text: "Name", value: "name", sortable: true },
            { text: "Code", value: "code", sortable: true },
            { text: "Option Values", value: "values", sortable: false },
            { text: "Created At", value: "created_at", sortable: false },
            { text: "Action", value: "action", sortable: false }
        ]
    }),

    created() {},

    methods: {
        paginate(e) {
            this.loading = true;
            axios
                .get(`/api/all/category/options?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.category_options = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        addOption() {
            this.formData = {
                name: "",
                values: []
            };
            this.dialog = true;
        },
        cancel() {
            this.confirm = false;
        },

        closeModel() {
            this.dialog = false;
            this.$refs.form.reset();
        },
        saveOptions() {
            if (this.$refs.form.validate()) {
                axios
                    .post("/api/store/options", this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.dialog = false;
                        this.paginate(this.$options);
                        this.formData = {
                            name: "",
                            values: []
                        };
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;

                        this.$toast.error(error.response.data.message, {
                            timeout: 2000
                        });

                        this.buttonLoading = false;
                    });
            }
        },
        confirmation(item) {
            console.log(item);
            this.confirm = true;
            this.option_id = item.id;
            //this.deleteItem(item);
        },
        editOption(item) {
            this.formData.id = item.id;
            this.formData.name = item.name;
            this.formData.values = item.values;
            this.dialog = true;
        },
        deleteItem() {
            axios
                .delete(`/api/delete/options/${this.option_id} `)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.paginate(this.$options);
                    this.option_id = "";
                    this.confirm = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;

                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                    this.confirm = false;
                });
        }
    }
};
</script>
