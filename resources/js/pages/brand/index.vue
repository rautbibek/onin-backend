<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-data-table
                :headers="headers"
                :items="brands.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'Brand per page',
                    'show-current-page': true,
                    'show-first-last-page': true
                }"
                @update:options="getBrands"
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
                                @click:append="getBrands"
                                @blur="getBrands"
                            ></v-text-field>
                        </v-col>
                        <v-btn
                            class="ma-2"
                            @click="addBrand"
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

                <template v-slot:item.created_at="{ item }">
                    <span>{{ item.created_at }}</span>
                </template>

                <template v-slot:item.category="{ item }">
                    <span>
                        <v-chip class="ma-2" color="primary" label>
                            <v-icon left>
                                mdi-tag
                            </v-icon>
                            {{ item.category.name }}
                        </v-chip></span
                    >
                </template>
                <template v-slot:item.attacment="{ item }">
                    <span v-if="item.attacment">
                        <img
                            class="pa-3"
                            style="object-fit:cover"
                            height="60px"
                            width="60px"
                            :src="item.attacment"
                            alt="item.name"
                        />
                    </span>
                    <span v-else>N/A</span>
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
                                v-on="on"
                                @click="edit(item)"
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
                                @click="confirmation(item)"
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
                <v-form
                    ref="form"
                    @submit.prevent="saveBrand"
                    v-model="valid"
                    lazy-validation
                >
                    <v-card-title>
                        <span class="text-h5">Brand</span>
                        <v-spacer></v-spacer>
                        <v-icon left @click="closeModel">close</v-icon>
                    </v-card-title>

                    <v-card-text>
                        <ValidationErrors :errors="errors"></ValidationErrors>
                        <v-container> </v-container>

                        <Treeselect
                            class="mb-10 selectbox error"
                            style="height:35px"
                            required
                            v-model.number="formData.category_id"
                            :options="fetAllCategories"
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
                            :rules="[required('Brand name')]"
                            label="Brand Name"
                            required
                            outlined
                        ></v-text-field>
                        <v-row>
                            <v-col>
                                <v-file-input
                                    color="deep-purple accent-4"
                                    counter
                                    clearable
                                    @change="filePreview"
                                    label="Logo"
                                    placeholder="Select your files"
                                    append-icon="mdi-camera"
                                    outlined
                                    hide-spin-buttons
                                    prepend-icon
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
                            <img
                                v-if="url"
                                :src="url"
                                height="100px"
                                width="100px"
                                alt="logo"
                            />
                        </v-row>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="closeModel" color="error">
                            <v-icon left>mdi-cancel</v-icon>
                            Cancel
                        </v-btn>
                        <v-btn
                            type="submit"
                            color="success"
                            :loading="buttonLoading"
                        >
                            <v-icon left>save</v-icon>
                            Save
                        </v-btn>
                    </v-card-actions>
                </v-form>
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
import Treeselect from "@riophae/vue-treeselect";
import { mapGetters, mapActions } from "vuex";
import ConfirmationBox from "../../components/ConfirmationBox.vue";
import { commonMixin } from "../../mixins/commonMixin";
import { validation } from "../../mixins/validationMixin";
export default {
    name: "Index",
    mixins: [commonMixin, validation],
    components: {
        ConfirmationBox,
        Treeselect
    },
    data: () => ({
        url: "",
        brand_id: "",
        confirm: false,
        search_keyword: "",
        title: "Brands",
        dialog: false,
        loading: false,
        errors: [],
        meta: [],
        brands: [],
        formData: {},
        categories: [],

        formTitle: "Brand",
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "Brand",
                disabled: true,
                to: "/brand"
            }
        ],

        headers: [
            { text: "ID", align: "start", value: "id", sortable: false },
            { text: "Name", value: "name", sortable: true },
            {
                text: "Category",
                value: "category",
                sortable: false
            },
            { text: "Logo", value: "attacment" },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ]
    }),

    computed: mapGetters(["fetAllCategories"]),

    created() {
        this.getCategories();
    },

    methods: {
        filePreview(e) {
            this.url = "";
            this.formData.logo = "";
            if (e) {
                this.formData.logo = e;
                this.url = URL.createObjectURL(e);
            }
        },

        ...mapActions(["getCategories"]),

        addBrand() {
            this.dialog = true;
        },
        closeModel() {
            this.dialog = false;
            this.$refs.form.reset();
        },
        getBrands(e) {
            this.loading = true;
            axios
                .get(`/api/brand?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.brands = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },

        cancel() {
            this.confirm = false;
            this.brand_id = "";
        },

        saveBrand() {
            if (
                this.formData.category_id &&
                this.formData.category_id != "undefined"
            ) {
            } else {
                this.$toast.error("Choose at least one category", {
                    timeout: 2000
                });
                return;
            }
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
                    .post(`/api/brand`, formData, config)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.buttonLoading = false;

                        this.closeModel();
                        this.formData = {};
                        this.$refs.form.reset();
                        this.getBrands(this.$options);
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
        edit(item) {
            this.formData.id = item.id;
            this.formData.name = item.name;
            this.formData.category_id = item.category.id;
            this.addBrand();
        },
        confirmation(item) {
            this.confirm = true;
            this.brand_id = item.id;
            //this.deleteItem(item);
        },

        deleteItem() {
            axios
                .delete(`api/brand/${this.brand_id} `)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.getBrands(this.$options);
                    this.brand_id = "";
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

        // ...mapActions(["fetchUsers"])
    }
};
</script>
