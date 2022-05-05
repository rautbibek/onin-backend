<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card>
            <template>
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-left">
                                    #ID
                                </th>
                                <th class="text-left">
                                    Collection Name
                                </th>
                                <th class="text-left">
                                    Product Title
                                </th>

                                <th class="text-left">
                                    Discount
                                </th>

                                <th class="text-left">
                                    Product Cover
                                </th>
                                <th class="text-left">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item,
                                index) in collection_product.product"
                                :key="index"
                            >
                                <td>{{ index + 1 }}</td>
                                <td>{{ collection_product.name }}</td>
                                <td>{{ item.title }}</td>
                                <td>
                                    <span
                                        v-if="
                                            item.discount <= 0 ||
                                                item.discount == null
                                        "
                                        >0</span
                                    >
                                    <span v-else>
                                        <span
                                            v-if="
                                                item.discount_type == 'percent'
                                            "
                                        >
                                            {{ item.discount }} %
                                        </span>
                                        <span v-else>
                                            Rs. {{ item.discount }}
                                        </span>
                                    </span>
                                </td>
                                <td>
                                    <v-img
                                        height="80"
                                        width="80"
                                        class="ma-5"
                                        :src="
                                            item.cover
                                                ? `/storage/thumb/${item.cover}`
                                                : '/images/no-image.png'
                                        "
                                        :alt="item.title"
                                    ></v-img>
                                </td>
                                <td>
                                    <v-btn
                                        style="text-decoration:none"
                                        x-small
                                        router
                                        :to="
                                            `/product/edit/${item.pivot.product_id}`
                                        "
                                        fab
                                        color="primary"
                                        dark
                                    >
                                        <v-icon dark>
                                            mdi-pencil
                                        </v-icon>
                                    </v-btn>
                                    <v-btn
                                        @click="
                                            removeProductFromCollection(item)
                                        "
                                        fab
                                        x-small
                                        dark
                                        color="error"
                                    >
                                        <v-icon>delete</v-icon>
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </template>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            collection_product: {},
            breadcrumb: [
                {
                    text: "Dashboard",
                    disabled: false,
                    to: "/dashboard"
                },
                {
                    text: "Collection",
                    disabled: false,
                    to: "/collection"
                },
                {
                    text: "Collection product",
                    to: "/collection",
                    disabled: true
                }
            ]
        };
    },
    methods: {
        getProduct() {
            axios
                .get(`/api/collection/${this.$route.params.id}`)
                .then(res => {
                    this.collection_product = res.data;
                })
                .catch(error => {
                    this.$toast.error(error.response.data.errors, {
                        timeout: 2000
                    });
                });
        },
        removeProductFromCollection(item) {
            if (confirm("are you sure to want to remove form ")) {
                axios
                    .post(
                        `/api/remove/collection/product/${this.collection_product.id}`,
                        {
                            product_id: item.id
                        }
                    )
                    .then(res => {
                        this.getProduct();
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                    })
                    .catch(error => {
                        this.$toast.error(error.response.data.errors, {
                            timeout: 2000
                        });
                    });
            }
        }
    },
    created() {
        this.getProduct();
    }
};
</script>

<style lang="scss" scoped></style>
