<template>
    <div>
        <!-- <b-link :items="breadcrumb"></b-link> -->
        <v-card>
            <v-data-table
                :headers="headers"
                :items="colors.data"
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
                            @click="addColorFamily"
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
                <template v-slot:item.sample="{ item }">
                    <div
                        style="height:50px; width:50px; border-radius:50%; margin:5px"
                        :style="{ background: item.code }"
                    ></div>
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
                                @click="editColor(item)"
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
                            :rules="[required('Color Name')]"
                            label="Color Name"
                            outlined
                        ></v-text-field>
                        <v-text-field
                            v-model="formData.code"
                            :rules="[required('Color Code')]"
                            label="Color Code"
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
                        @click="saveColor"
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
    name: "ColorFamily",
    mixins: [commonMixin, validation],
    components: {
        ConfirmationBox
    },
    data: () => ({
        search_keyword: "",
        title: "Color Families",
        dialog: false,
        color_id: null,
        loading: false,
        valid: true,
        confirm: false,
        meta: [],
        colors: [],

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
            { text: "Smple", value: "sample", sortable: false },
            { text: "Created At", value: "created_at", sortable: false },
            { text: "Action", value: "action", sortable: false }
        ]
    }),

    created() {},

    methods: {
        paginate(e) {
            this.loading = true;
            axios
                .get(`/api/colors?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.colors = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        addColorFamily() {
            this.dialog = true;
        },
        cancel() {
            this.confirm = false;
        },

        closeModel() {
            this.$refs.form.reset();
            this.dialog = false;
        },
        saveColor() {
            if (this.$refs.form.validate()) {
                axios
                    .post("/api/colors", this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.closeModel();
                        this.paginate(this.$options);
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
            this.confirm = true;
            this.color_id = item.id;
            //this.deleteItem(item);
        },
        editColor(item) {
            //console.log(item);
            this.formData.id = item.id;
            this.formData.name = item.name;
            this.formData.code = item.code;
            this.dialog = true;
        },
        deleteItem() {
            axios
                .delete(`api/colors/${this.color_id} `)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.paginate(this.$options);
                    this.color_id = "";
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
