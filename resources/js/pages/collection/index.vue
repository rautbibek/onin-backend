<template>
    <div>
        <!-- <b-link :items="breadcrumb"></b-link> -->
        <v-card>
            <v-data-table
                :headers="headers"
                :items="collections.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'collection per page',
                    'show-current-page': true,
                    'show-first-last-page': true
                }"
                @update:options="getCollections"
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
                                outlined
                                dense
                                append-icon="search"
                                placeholder="Start typing..."
                                @click:append="getCollections"
                                @blur="getCollections"
                            ></v-text-field>
                        </v-col>
                        <v-btn
                            class="ma-2"
                            @click="addCollection"
                            fab
                            x-small
                            color="success"
                        >
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                    </v-toolbar>
                </template>
                <template v-slot:item.id="{ index }">
                    <span>{{ index + meta.from }}</span>
                </template>
                <template v-slot:item.created_at="{ item }">
                    <span>{{ item.created_at }}</span>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                x-small
                                fab
                                color="primary"
                                @click="edit(item)"
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
        <v-dialog v-model="collectionModal" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Collection</span>
                    <v-spacer></v-spacer>
                    <v-icon left @click="closeModel">close</v-icon>
                </v-card-title>

                <v-card-text>
                    <ValidationErrors :errors="errors"></ValidationErrors>
                    <v-container> </v-container>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            v-model="formData.name"
                            :rules="[required('Collection Name')]"
                            label="Collection Name"
                            required
                            outlined
                        ></v-text-field>
                        <v-select
                            :items="discount_types"
                            :item-text="'name'"
                            :item-value="'value'"
                            v-model="formData.discount_type"
                            outlined
                            clearable
                            label="Discount Type"
                        ></v-select>
                        <v-text-field
                            type="number"
                            v-model="formData.discount"
                            label="Discount"
                            outlined
                            clearable
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
                        @click="saveCollection"
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
    mixins: [commonMixin, validation],
    components: {
        ConfirmationBox
    },
    data: () => ({
        confirm: false,
        collectionModal: false,
        search_keyword: "",
        errors: [],
        title: "Collections",
        dialog: false,
        collection_id: null,
        loading: false,
        meta: [],
        collections: [],
        formTitle: "Collections",
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                href: "/dashboard"
            },
            {
                text: "Collection",
                disabled: true,
                href: "/collection"
            }
        ],
        discount_types: [
            {
                name: "Percentage",
                value: "per"
            },
            {
                name: "Rupees",
                value: "rs"
            }
        ],

        headers: [
            { text: "id", align: "start", value: "id", sortable: false },
            { text: "Name", value: "name", sortable: true },
            { text: "Discount Type", value: "discount_type" },
            { text: "Discount", value: "discount" },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ]
    }),

    methods: {
        getCollections(e) {
            this.loading = true;
            axios
                .get(`/api/collection?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.collections = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        addCollection() {
            this.formData = {};

            this.collectionModal = true;
        },
        closeModel() {
            this.$refs.form.reset();
            this.collectionModal = false;
        },
        saveCollection() {
            if (this.$refs.form.validate()) {
                this.buttonLoading = true;
                axios
                    .post(`/api/collection`, this.formData)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.buttonLoading = false;

                        this.closeModel();
                        this.$refs.form.reset();
                        this.getCollections(this.$options);
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
            this.collection_id = item.id;
            //this.deleteItem(item);
        },
        edit(item) {
            this.formData = {};
            this.formData.discount_type = item.discount_type;
            this.formData.name = item.name;
            this.formData.id = item.id;
            this.formData.discount = item.discount;
            this.collectionModal = true;
        },
        cancel() {
            this.confirm = false;
            this.brand_id = "";
        },
        deleteItem() {
            axios
                .delete(`api/collection/${this.collection_id} `)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.getCollections(this.$options);
                    this.collection_id = "";
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
