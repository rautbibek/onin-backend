<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <v-data-table
                :headers="headers"
                :items="product.data"
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
                                outlined
                                dense
                                clearable
                                append-icon="search"
                                placeholder="Start typing..."
                                @click:append="paginate"
                                @blur="paginate"
                            ></v-text-field>
                        </v-col>
                        <div class="mt-2">
                            <v-btn
                                class="ma-2"
                                to="product/add"
                                fab
                                x-small
                                color="success"
                            >
                                <v-icon>mdi-plus</v-icon>
                            </v-btn>
                            <v-btn
                                @click="paginate"
                                fab
                                x-small
                                color="primary"
                            >
                                <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                        </div>
                    </v-toolbar>
                </template>
                <template v-slot:item.id="{ index }">
                    <span>{{ index + meta.from }}</span>
                </template>
                <template v-slot:item.image="{ item }">
                    <v-img
                        class="ma-2"
                        :src="item.image"
                        :lazy-src="item.image"
                        max-width="100"
                        max-height="100"
                    >
                        <template v-slot:placeholder>
                            <v-row
                                class="fill-height ma-0"
                                align="center"
                                justify="center"
                            >
                                <v-progress-circular
                                    indeterminate
                                    color="grey lighten-5"
                                ></v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                </template>
                <template v-slot:item.status="{ item }">
                    <v-switch
                        class="text-right"
                        :input-value="item.status"
                        value
                        color="red"
                        inset
                        @click="updateStatus(item)"
                    ></v-switch>
                </template>
                <template v-slot:item.variant="{ item }">
                    <span>{{ item.variant.length }}</span>
                </template>

                <template v-slot:item.category_id="{ item }">
                    <span>{{ item.category }}</span>
                </template>
                <template v-slot:item.brand_id="{ item }">
                    <span>{{ item.brand }}</span>
                </template>

                <template v-slot:item.action="{ item }">
                    <v-btn x-small fab color="view" @click="detail(item)" dark>
                        <v-icon dark>
                            mdi-eye
                        </v-icon>
                    </v-btn>

                    <v-btn
                        x-small
                        router
                        :to="`/product/edit/${item.id}`"
                        fab
                        color="primary"
                        dark
                    >
                        <v-icon dark>
                            mdi-pencil
                        </v-icon>
                    </v-btn>

                    <v-btn x-small fab color="error" dark>
                        <v-icon dark>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>
        <ProductDetail
            :product_detail_dialog="view_detail"
            :detail="current_product"
            v-if="view_detail"
        >
            <div slot="toolbar">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>Product Detail</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark text @click="view_detail = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
            </div>
        </ProductDetail>
    </div>
</template>
<script>
import axios from "axios";
import ProductDetail from "./ProductDetail.vue";
export default {
    components: { ProductDetail },
    data: () => ({
        search_keyword: "",
        title: "Products",
        dialog: false,
        loading: false,
        meta: [],
        product: [],
        view_detail: false,
        current_product: [],
        formTitle: "Products",
        breadcrumb: [
            {
                text: "Dashboard",
                disabled: false,
                to: "/dashboard"
            },
            {
                text: "Product",
                disabled: true,
                to: "/product"
            }
        ],

        headers: [
            { text: "id", align: "start", value: "id", sortable: false },
            { text: "Name", value: "title", sortable: true },
            { text: "Category", value: "category_id" },
            { text: "Brand", value: "brand_id" },
            { text: "Image", value: "image" },
            { text: "Available Stock", value: "inventory_track" },
            { text: "Total Variant", value: "variant" },
            { text: "Status", value: "status" },
            // { text: "Date", value: "created_at" },
            { text: "Action", value: "action", align: "right" }
        ]
    }),

    methods: {
        paginate(e) {
            console.log(e);
            this.loading = true;
            axios
                .get(`/api/product?page=${e.page}`, {
                    params: {
                        per_page: e.itemsPerPage,
                        sortBy: e.sortBy,
                        orderByDesc: e.sortDesc,
                        search_keyword: this.search_keyword
                    }
                })
                .then(res => {
                    this.product = res.data;
                    this.meta = res.data.meta;
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                });
        },
        updateStatus(item) {
            axios
                .post(`/api/product/status/${item.id}`)
                .then(res => {})
                .catch();
        },
        detail(item) {
            this.view_detail = true;
            this.current_product = item;
        }
    }
};
</script>
