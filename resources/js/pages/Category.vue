<template>
    <div>
        <b-link :items="breadcrumb"></b-link>

        <v-app-bar elevation="4">
            <v-card-title>Categories</v-card-title>
            <v-spacer> </v-spacer>
            <v-btn class="ma-2" @click="openDialog" fab x-small color="success">
                <v-icon>mdi-plus</v-icon>
            </v-btn>
            <v-btn @click="getMenu" fab x-small color="primary">
                <v-icon>mdi-refresh</v-icon>
            </v-btn>
        </v-app-bar>

        <v-card class="mx-auto">
            <v-treeview
                selectionType="independent"
                :items="menu"
                selected-color="indigo"
                open-on-click
                :loading="loading"
                selectable
                hoverable
                activatable
                return-object
                expand-icon="mdi-chevron-down"
                on-icon="mdi-bookmark"
                off-icon="mdi-bookmark-outline"
                indeterminate-icon="mdi-bookmark-minus"
            >
                <template v-slot:append="{ item }">
                    <template>
                        <!-- <img
                            height="70px"
                            width="70px"
                            :src="item.cover"
                            :alt="item.cover"
                            style="padding:10px"
                        /> -->
                        <v-btn
                            x-small
                            fab
                            color="teal"
                            dark
                            @click="openOptionDialog(item)"
                        >
                            <v-icon dark>
                                mdi-cog
                            </v-icon>
                        </v-btn>
                        <v-btn
                            x-small
                            fab
                            color="primary"
                            dark
                            @click="editItem(item)"
                        >
                            <v-icon dark>
                                mdi-pencil
                            </v-icon>
                        </v-btn>
                        <v-btn
                            @click="deleteItem(item)"
                            x-small
                            fab
                            color="error"
                            dark
                        >
                            <v-icon dark>
                                mdi-delete
                            </v-icon>
                        </v-btn>
                    </template>
                </template>
            </v-treeview>
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
                        <Treeselect
                            class="mb-10 selectbox"
                            style="height:35px"
                            v-model="formData.parent_id"
                            :options="fetAllCategories"
                            :disable-branch-nodes="false"
                            :show-count="true"
                            :rules="[required('category name')]"
                        >
                            <div slot="value-label" slot-scope="{ node }">
                                {{ node.raw.name }}
                            </div>
                            <label slot="option-label" slot-scope="{ node }">
                                {{ node.raw.name }}
                            </label>
                        </Treeselect>

                        <v-text-field
                            v-model="formData.name"
                            :rules="[required('category name')]"
                            label="Category Name"
                            required
                            outlined
                        ></v-text-field>
                        <div
                            v-if="formData.cover_url"
                            style="justify-content: center; display: flex; "
                            class="ma-4"
                        >
                            <v-img
                                lazy-src="https://picsum.photos/id/11/10/6"
                                max-height="130"
                                max-width="130"
                                :src="formData.cover_url"
                            ></v-img>
                        </div>
                        <v-row>
                            <!-- <v-col>
                                <v-text-field
                                    v-model="formData.icon"
                                    :rules="[required('Icon')]"
                                    label="icon"
                                    required
                                    outlined
                                ></v-text-field>
                            </v-col> -->

                            <v-col>
                                <v-file-input
                                    v-model="formData.image"
                                    color="deep-purple accent-4"
                                    counter
                                    label="Cover Image"
                                    placeholder="Select your files"
                                    prepend-icon="mdi-camera"
                                    outlined
                                    show-size
                                    accept="image/*"
                                >
                                    <template
                                        v-slot:selection="{ index, text }"
                                    >
                                        <v-chip
                                            v-if="index < 2"
                                            color="deep-purple accent-4"
                                            dark
                                            label
                                            small
                                        >
                                            {{ text }}
                                        </v-chip>

                                        <span
                                            v-else-if="index === 2"
                                            class="text-overline grey--text text--darken-3 mx-2"
                                        >
                                            +{{ files.length - 2 }} File(s)
                                        </span>
                                    </template>
                                </v-file-input>
                            </v-col>
                        </v-row>
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
        <v-dialog
            v-model="optionDialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
        >
            <v-card tile>
                <v-toolbar flat dark color="primary" dense>
                    <v-toolbar-title
                        ><strong>{{ category_name }} </strong> option setting
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon dark @click="optionDialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-list three-line subheader>
                        <v-subheader>Category Option Controls</v-subheader>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-subtitle
                                    >Choose if this category contains color
                                    attribute</v-list-item-subtitle
                                >
                                <v-list-item-action>
                                    <v-switch
                                        v-model="has_color"
                                        label="Has Color"
                                        color="red"
                                        hide-details
                                    ></v-switch>
                                </v-list-item-action>

                                <v-list-item-subtitle
                                    >Choose if this category contains sizes
                                    attribute</v-list-item-subtitle
                                >
                                <v-list-item-subtitle>
                                    <v-switch
                                        v-model="has_size"
                                        label="Has Sizes"
                                        color="red"
                                        hide-details
                                    ></v-switch>
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                    <v-divider></v-divider>
                    <v-subheader>Choose your options</v-subheader>
                    <v-row no-gutters>
                        <v-col
                            md="4"
                            sm="12"
                            class="pa-4"
                            v-for="(option, index) in options"
                            :key="index"
                            cols="12"
                        >
                            <v-checkbox
                                :value="option.id"
                                :label="option.code"
                                v-model="category_options"
                            ></v-checkbox>
                        </v-col>
                    </v-row>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="optionDialog = false" color="error">
                            <v-icon left>mdi-cancel</v-icon>
                            Cancel
                        </v-btn>
                        <v-btn
                            color="success"
                            @click="updateCategoryOptions"
                            :loading="buttonLoading"
                        >
                            <v-icon left>save</v-icon>
                            proceed
                        </v-btn>
                    </v-card-actions>
                </v-card-text>

                <div style="flex: 1 1 auto;"></div>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";
import { mapGetters, mapActions } from "vuex";
import { commonMixin } from "../mixins/commonMixin";
import { validation } from "../mixins/validationMixin";
export default {
    mixins: [commonMixin, validation],
    components: {
        Treeselect
    },

    data: () => ({
        test: [],
        tree: [],
        search_keyword: "",
        title: "Categories",
        dialog: false,
        id: null,
        category_name: "",
        loading: false,
        optionDialog: false,
        has_color: false,
        has_size: false,
        options: [],
        category_options: [],
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "Category",
                disabled: true,
                to: "/category"
            }
        ],
        headers: [
            { text: "id", align: "start", value: "id", sortable: false },
            { text: "Root", value: "root", sortable: false },
            { text: "Sub Root", value: "sub_root" },
            { text: "Children", value: "child" },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ],
        meta: [],

        formTitle: "Categories",
        dialog: false,
        image: [],
        menu: []
    }),

    computed: mapGetters(["fetAllCategories"]),

    methods: {
        onOpen(e) {
            // ignore initial open
            if (!this.__initial) {
                this.__initial = true;
                return;
            }
        },

        getMenu() {
            this.loading = true;
            axios
                .get(`/api/category`, {
                    params: {
                        // per_page: e.itemsPerPage,
                        // sortBy: e.sortBy,
                        // orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.menu = res.data.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },

        getOptions() {
            axios
                .get("all/options")
                .then(res => {
                    this.options = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.errors, {
                        timeout: 2000
                    });
                });
        },

        deleteItem(item) {
            let confirmAction = confirm(
                "Are you sure to want to delete this ?"
            );
            if (confirmAction) {
                axios
                    .delete(`api/category/${item.id}`)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 5000
                        });
                        //this.paginate(this.$options);
                        this.getCategories();
                        this.getMenu(this.$options);
                    })
                    .catch(error => {
                        this.$toast.error(error.response.data.error, {
                            timeout: 2000
                        });
                    });
            }
        },
        editItem(item) {
            this.formData.id = item.id;
            this.formData.parent_id = item.parent_id;
            this.formData.cover = item.cover;
            this.formData.cover_url = item.cover_url;
            this.formData.name = item.name;
            this.openDialog();
        },

        submit() {
            if (this.$refs.form.validate()) {
                this.buttonLoading = true;
                var formData = new FormData();
                const config = {
                    headers: {
                        "content-type": "multipart/form-data"
                    }
                };
                _.each(this.formData, (value, key) => {
                    formData.append(key, value);
                });

                axios
                    .post("api/category", formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.buttonLoading = false;
                        this.closeModel();
                        this.formData = {};
                        this.getCategories();
                        this.getMenu(this.$options);
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

        async openOptionDialog(item) {
            this.id = item.id;
            await axios
                .get(`/api/category/${this.id}`)
                .then(res => {
                    this.category_options = res.data;
                })
                .catch(error => {});
            this.category_name = item.name;
            this.has_color = item.has_color;
            this.has_size = item.has_size;

            this.optionDialog = true;
        },

        updateCategoryOptions() {
            axios
                .post(`api/update/category/options`, {
                    id: this.id,
                    has_color: this.has_color,
                    has_size: this.has_size,
                    category_options: this.category_options
                })
                .then(res => {
                    this.getMenu();
                    this.optionDialog = false;

                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                })
                .catch(error => {
                    this.$toast.error(error.response.data.message, {
                        timeout: 2000
                    });
                });
        },

        ...mapActions(["getCategories"]),

        showButtons(item) {
            return item.children != null && item.children.length > 0;
        }
    },
    mounted() {
        this.getOptions();
    },

    created() {
        this.getCategories();
        this.getMenu();
    }
};
</script>
<style>
.vue-treeselect__input {
    height: 50px !important;
    align-items: flex-start;
    display: flex;
    flex: 1 1 auto;
    font-size: 16px;
    letter-spacing: normal;
}
.vue-treeselect__menu-container {
    font-size: 16px;
    color: black;
}
.vue-treeselect__x-container {
    color: black;
    font-size: 20px;
}
.vue-treeselect__control:hover {
    border: 1px solid black;
}
.vue-treeselect__control:focus {
    border: 4px solid blue;
    font-size: 20px;
}
.vue-treeselect__control {
    border: 1px solid #897272;
}
</style>
