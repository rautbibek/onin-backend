<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-data-table
                :headers="headers"
                :items="banners.data"
                class="elevation-5"
                :server-items-length="meta.total"
                loading-text="Loading... Please wait"
                :items-per-page="25"
                :loading="loading"
                show-current-page="true"
                :footer-props="{
                    'items-per-page-options': [5, 10, 20, 25, 50, 100],
                    'items-per-page-text': 'banner per page',
                    'show-current-page': true,
                    'show-first-last-page': true
                }"
                @update:options="getBanners"
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
                                @click:append="getBanners"
                                @blur="getBanners"
                            ></v-text-field>
                        </v-col>
                        <v-btn
                            class="ma-2"
                            @click="addBanner"
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

                <template v-slot:item.title="{ item }">
                    <span v-if="item.title">{{ item.title }}</span>
                    <span v-else>N/A</span>
                </template>
                <template v-slot:item.subtitle="{ item }">
                    <span v-if="item.subtitle">{{ item.subtitle }}</span>
                    <span v-else>N/A</span>
                </template>

                <template v-slot:item.link="{ item }">
                    <a v-if="item.link" :href="item.link">{{ item.link }}</a>
                    <span v-else>N/A</span>
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
                <template v-slot:item.attachment="{ item }">
                    <span v-if="item.attachment">
                        <img
                            class="pa-3"
                            style="object-fit:cover"
                            height="60px"
                            width="60px"
                            :src="item.attachment"
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
                <v-card-title>
                    <span class="text-h5">Banner</span>
                    <v-spacer></v-spacer>
                    <v-icon left @click="closeModel">close</v-icon>
                </v-card-title>

                <v-card-text>
                    <ValidationErrors :errors="errors"></ValidationErrors>
                    <v-container> </v-container>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-autocomplete
                            v-model="formData.type"
                            :items="type"
                            item-text="name"
                            item-value="value"
                            label=" Banner Type *"
                            :rules="[required('Banner Type')]"
                            outlined
                        >
                        </v-autocomplete>

                        <v-text-field
                            v-model="formData.title"
                            label="Banner Title"
                            outlined
                        ></v-text-field>

                        <v-text-field
                            v-model="formData.subtitle"
                            label="Banner Subtitle"
                            outlined
                        ></v-text-field>
                        <v-text-field
                            v-model="formData.link"
                            label="Redirection Link"
                            outlined
                        ></v-text-field>
                        <v-row>
                            <v-col>
                                <v-file-input
                                    color="deep-purple accent-4"
                                    counter
                                    clearable
                                    @change="filePreview"
                                    label="Banner Image"
                                    placeholder="Select your image"
                                    append-icon="mdi-camera"
                                    outlined
                                    hide-spin-buttons
                                    prepend-icon
                                    show-size
                                    accept="image/jpt, image/jpeg, image/png"
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
                                alt="banner"
                            />
                        </v-row>
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
                        @click="saveBanner"
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
    name: "Index",
    mixins: [commonMixin, validation],
    components: {
        ConfirmationBox
    },
    data: () => ({
        url: "",
        banner_id: "",
        confirm: false,
        search_keyword: "",
        title: "Banners",
        dialog: false,
        loading: false,
        type: [
            {
                name: "Home",
                value: "home"
            },
            {
                name: "AD",
                value: "ad"
            },
            {
                name: "Offer",
                value: "offer"
            },
            {
                name: "Other",
                value: "other"
            }
        ],
        errors: [],
        meta: [],
        banners: [],
        formData: {},
        categories: [],

        formTitle: "Banner",
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "Banner",
                disabled: true,
                to: "/Banner"
            }
        ],

        headers: [
            { text: "ID", align: "start", value: "id", sortable: false },
            { text: "Banner Type", value: "type", sortable: true },
            { text: "Title", value: "title", sortable: true },
            { text: "Subtitle", value: "subtitle", sortable: true },
            { text: "Redirect Url", value: "link", sortable: true },
            { text: "Image", value: "attachment", sortable: false },
            { text: "Created At", value: "created_at" },
            { text: "Action", value: "action" }
        ]
    }),

    //computed: mapGetters(["allBrnads"]),

    created() {
        this.getCategory();
    },

    methods: {
        filePreview(e) {
            this.url = "";
            this.formData.image = "";
            if (e) {
                this.formData.image = e;
                this.url = URL.createObjectURL(e);
            }
        },
        addBanner() {
            this.dialog = true;
        },
        closeModel() {
            this.dialog = false;
            this.$refs.form.reset();
        },
        getBanners(e) {
            console.log("loading banner");
            this.loading = true;
            axios
                .get(`/api/banner?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.banners = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        // getCategory() {
        //     axios
        //         .get("/api/select/category")
        //         .then(res => {
        //             this.categories = res.data;
        //         })
        //         .catch(error => {
        //             console.log(error);
        //         });
        // },

        cancel() {
            this.confirm = false;
            this.banner_id = "";
        },

        saveBanner() {
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
                    .post(`/api/banner`, formData, config)
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                        this.buttonLoading = false;

                        this.closeModel();
                        this.$refs.form.reset();
                        this.formData = {};
                        this.errors = [];
                        this.getBanners(this.$options);
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
            this.formData.type = item.type;
            this.formData.title = item.title;
            this.formData.subtitle = item.subtitle;
            this.formData.link = item.link;
            this.url = item.attachment;
            // this.formData. = item.category.id;
            this.addBanner();
        },
        confirmation(item) {
            this.confirm = true;
            this.banner_id = item.id;
            //this.deleteItem(item);
        },

        deleteItem() {
            axios
                .delete(`api/banner/${this.banner_id} `)
                .then(res => {
                    this.$toast.success(res.data.message, {
                        timeout: 2000
                    });
                    this.getBanners(this.$options);
                    this.banner_id = "";
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
