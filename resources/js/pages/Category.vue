<template>
    <div>
        <v-card>
            <v-data-table
                :headers="headers"
                :items="users.data"
                :options.sync="options"
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
                    <v-app-bar flat color="white">
                        <v-toolbar-title>{{ title }}</v-toolbar-title>

                        <v-spacer></v-spacer>
                        <v-card style="background:none" flat>
                            <v-text-field
                                style="width:400px; margin-right:10px"
                                v-model="search_keyword"
                                class="mt-6"
                                label="search"
                                dense
                                outlined
                                append-icon="search"
                                clearable
                                placeholder="Start typing..."
                                @click:append="paginate"
                                @blur="paginate"
                            ></v-text-field>
                        </v-card>
                        <v-btn
                            class="ma-2"
                            @click="openDialog"
                            fab
                            x-small
                            color="success"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                        <v-btn fab x-small color="primary">
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn>
                    </v-app-bar>
                </template>
                <template v-slot:item.id="{ index }">
                    <span>{{ index + meta.from }}</span>
                </template>
                <template v-slot:item.created_at="{ item }">
                    <span>{{ item.created_at }}</span>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="editItem(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon small @click="deleteItem(item)">
                        mdi-delete
                    </v-icon>
                </template>
                <!-- <template v-slot:item.action="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                x-small
                                fab
                                color="view"
                                dark
                                v-bind="attrs"
                                v-on="on"
                            >
                                <v-icon dark>
                                    mdi-eye
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>View </span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                x-small
                                fab
                                color="primary"
                                dark
                                v-bind="attrs"
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
                                v-on="on"
                                @click="alert(item.row)"
                            >
                                <v-icon dark>
                                    mdi-delete
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Delete </span>
                    </v-tooltip>
                </template> -->
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Category</span>
                    <v-spacer></v-spacer>
                    <v-icon left @click="closeModel">close</v-icon>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <ValidationErrors :errors="errors"></ValidationErrors>
                    <v-container> </v-container>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-autocomplete
                            v-model="formData.parent_id"
                            :items="allCategories"
                            :item-text="'name'"
                            :item-value="'id'"
                            outlined
                            label="Root category"
                        ></v-autocomplete>

                        <v-text-field
                            v-model="formData.name"
                            :rules="[required('category name')]"
                            label="Category Name"
                            required
                            outlined
                        ></v-text-field>

                        <!-- <v-checkbox
                            v-model="formData.is_featured"
                            label="is featured"
                            required
                        ></v-checkbox> -->
                    </v-form>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="closeModel" color="error">
                        <v-icon left>mdi-cancel</v-icon>
                        Cancel
                    </v-btn>
                    <v-btn
                        color="success"
                        @click="submit"
                        :loading="buttonLoading"
                    >
                        <v-icon left>save</v-icon>
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import { commonMixin } from "../mixins/commonMixin";
import { validation } from "../mixins/validationMixin";
export default {
    mixins: [commonMixin, validation],
    data: () => ({
        search_keyword: "",
        title: "Categories",
        dialog: false,
        loading: false,
        meta: [],
        users: [],
        formTitle: "Categories",
        dialog: false,
        allCategories: [],

        headers: [
            { text: "id", align: "start", value: "id", sortable: false },
            {
                text: "Parent",
                align: "start",
                value: "parent_id",
                sortable: true
            },
            { text: "Name", value: "name", sortable: true },
            { text: "Slug", value: "slug" },
            { text: "Icon", value: "icon" },
            { text: "Cover", value: "cover" },
            { text: "Action", value: "actions", align: "center" }
        ]
    }),

    computed: mapGetters(["allUsers"]),

    created() {
        this.fetchUsers();
    },

    methods: {
        paginate(e) {
            this.loading = true;
            axios
                .get(`/api/category?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.users = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        getAllCategories() {
            axios
                .get("api/v1/category")
                .then(res => {
                    this.allCategories = res.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        deleteItem(item) {
            axios
                .delete(`api/category/${item.id}`)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.paginate(this.$options);
                })
                .catch(error => {
                    console.log("error");
                });
        },
        editItem(item) {
            this.formData = item;
            this.openDialog();
        },
        submit() {
            if (this.$refs.form.validate()) {
                this.buttonLoading = true;
                axios
                    .post("api/category", this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.buttonLoading = false;
                        this.paginate(this.options);
                        this.closeModel();
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

        ...mapActions(["fetchUsers"])
    },
    created() {
        this.getAllCategories();
    }
};
</script>
