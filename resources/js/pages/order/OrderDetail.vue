<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        <v-card min-height="500" flat>
            <v-card-title
                >Order Detail #{{ order.order_identifier }}</v-card-title
            >
            <v-card-subtitle>{{
                order.created_at | moment("dddd, MMMM Do YYYY, h:mm:ss a")
            }}</v-card-subtitle>

            <v-card-text>
                <v-row>
                    <v-col cols="8">
                        <v-card class="mx-auto" min-height="300" outlined>
                            <div class="text-overline mb-4 px-5 mt-4">
                                Order Items
                            </div>

                            <v-divider></v-divider>

                            <v-simple-table fixed-header height="300px">
                                <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th class="text-left">
                                                Product
                                            </th>
                                            <th class="text-left">
                                                Varinat
                                            </th>
                                            <th class="text-left">
                                                QTY
                                            </th>
                                            <th class="text-left">
                                                Price
                                            </th>
                                            <th class="text-left">
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(product,
                                            index) in order.order_detail"
                                            :key="index"
                                        >
                                            <td>
                                                {{ product.title }}
                                                ({{ product.sku }})
                                            </td>
                                            <td>
                                                {{
                                                    product.variant_color
                                                        ? product.variant_color
                                                        : "-"
                                                }}
                                            </td>
                                            <td>{{ product.quantity }}</td>
                                            <td>
                                                Rs
                                                {{
                                                    product.price | formatNumber
                                                }}
                                            </td>
                                            <td>
                                                Rs.{{
                                                    (product.price *
                                                        product.quantity)
                                                        | formatNumber
                                                }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>

                                            <th colspan="2">Delivery Charge</th>
                                            <th>
                                                Rs.{{
                                                    order.delivery_charge
                                                        | formatNumber
                                                }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>

                                            <th colspan="2">Total Price</th>
                                            <th>
                                                Rs.{{
                                                    (order.total_price +
                                                        order.delivery_charge)
                                                        | formatNumber
                                                }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-card>
                        <v-card class="mt-5" flat>
                            <v-card-title>Order Status</v-card-title>
                            <v-stepper alt-labels>
                                <v-stepper-header>
                                    <v-divider></v-divider>
                                    <v-stepper-step
                                        step="1"
                                        :complete="
                                            order.status >= 1 &&
                                                order.status <= 5
                                        "
                                    >
                                        Processing
                                    </v-stepper-step>

                                    <v-divider></v-divider>

                                    <v-stepper-step
                                        step="2"
                                        :complete="
                                            order.status >= 2 &&
                                                order.status <= 5
                                        "
                                    >
                                        Packing
                                    </v-stepper-step>

                                    <v-divider></v-divider>
                                    <v-stepper-step
                                        step="3"
                                        :complete="
                                            order.status >= 3 &&
                                                order.status <= 5
                                        "
                                    >
                                        Shipping
                                    </v-stepper-step>

                                    <v-divider></v-divider>
                                    <v-stepper-step
                                        step="4"
                                        :complete="order.status == 4"
                                    >
                                        Completed
                                    </v-stepper-step>

                                    <v-divider></v-divider>
                                </v-stepper-header>
                            </v-stepper>
                            <div
                                v-if="order.status <= 3"
                                class="text-right mt-3"
                            >
                                <v-btn
                                    color="primary"
                                    @click="nextSteep(order.status, order.id)"
                                    >next</v-btn
                                >
                            </div>
                        </v-card>
                        <div class="mt-5 text-right">
                            <v-form
                                @submit.prevent="postComment"
                                ref="comment"
                                v-model="valid"
                            >
                                <v-textarea
                                    name="input-7-1"
                                    outlined
                                    v-model="comment"
                                    label="Order Comment"
                                    auto-grow
                                    value="comment"
                                    :rules="[required('category name')]"
                                    required
                                ></v-textarea>

                                <v-btn type="submit" color="primary"
                                    >update comment</v-btn
                                >
                            </v-form>
                        </div>
                    </v-col>
                    <v-col cols="4">
                        <v-card class="mx-auto" outlined>
                            <div class="text-overline mb-4 px-5 mt-4">
                                Customer Detail
                            </div>
                            <v-divider></v-divider>
                            <v-list-item three-line>
                                <v-list-item-content>
                                    <v-list-item-title class="text-h5 mb-1">
                                        {{ order.order_by }}
                                    </v-list-item-title>
                                    <div class="text-overline mb-4">
                                        <v-icon left small
                                            >mdi-map-marker</v-icon
                                        >
                                        Delivery Address
                                    </div>

                                    <v-list-item-subtitle>
                                        {{
                                            order.delivery_address
                                        }}</v-list-item-subtitle
                                    >
                                </v-list-item-content>

                                <v-list-item-avatar size="100">
                                    <v-avatar size="100">
                                        <img
                                            src="/src/user.png"
                                            :alt="order.order_by"
                                        />
                                    </v-avatar>
                                </v-list-item-avatar>
                            </v-list-item>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-chip
                                    class="ma-2"
                                    color="primary"
                                    outlined
                                    pill
                                    small
                                >
                                    <v-icon left>
                                        email
                                    </v-icon>
                                    {{ order.email }}
                                </v-chip>
                                <v-chip
                                    class="ma-2"
                                    color="primary"
                                    outlined
                                    pill
                                    small
                                >
                                    <v-icon left>
                                        phone
                                    </v-icon>
                                    {{
                                        order.contact_number
                                            ? order.contact_number
                                            : "+977-0000000000"
                                    }}
                                </v-chip>
                            </v-card-actions>
                        </v-card>
                        <v-card class="mx-auto mt-3" outlined>
                            <div class="text-overline mb-4 px-5 mt-4">
                                Other Detail
                            </div>
                            <v-divider></v-divider>
                            <v-card-text>
                                <v-row>
                                    <v-col>
                                        <strong>Payment Type</strong>
                                    </v-col>
                                    <v-col>
                                        <span
                                            style="text-transform: uppercase;"
                                        >
                                            {{ order.payment_type }}
                                        </span>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <strong>Payment Status</strong>
                                    </v-col>
                                    <v-col>
                                        <span
                                            v-if="order.payment_status"
                                            style="color:green"
                                            >COMPLETED</span
                                        >
                                        <span v-else style="color:red"
                                            >PENDING</span
                                        >
                                    </v-col>
                                </v-row>
                                <v-col cols="12" v-if="!order.payment_status">
                                    <v-checkbox
                                        v-model="payment_status"
                                        @change="changePaymentStatus"
                                        label="Mark payment as completed"
                                    ></v-checkbox>
                                </v-col>
                            </v-card-text>
                            <v-divider></v-divider>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
        <v-dialog v-model="loading" hide-overlay persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Please stand by
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { validation } from "../../mixins/validationMixin";

export default {
    mixins: [validation],
    data() {
        return {
            payment_status: false,

            valid: false,
            loading: false,
            comment: "",
            breadcrumb: [
                {
                    text: "Dashboard",
                    disabled: false,
                    to: "/dashboard"
                },
                {
                    text: "Order",
                    disabled: false,
                    to: "/order"
                },
                {
                    text: "Order Detail",
                    to: "/order"
                }
            ],
            order: {}
        };
    },
    methods: {
        getOrderDetail() {
            axios
                .get(`/api/order/detail/${this.$route.params.id}`)
                .then(res => {
                    this.order = res.data;
                    this.comment = res.data.comment;
                    this.payment_status = res.data.payment_status;
                    this.$refs.comment.resetValidation();
                })
                .catch(error => {
                    console.log(error.response.data.message);
                });
        },
        postComment() {
            if (this.$refs.comment.validate()) {
                axios
                    .put(`/api/update/order/comment/${this.$route.params.id}`, {
                        comment: this.comment
                    })
                    .then(res => {
                        this.$toast.success(res.data.message, {
                            timeout: 5000
                        });
                    })
                    .catch(error => {
                        this.$toast.error(error.response.data.message, {
                            timeout: 5000
                        });
                    });
            }
        },
        changePaymentStatus() {
            this.loading = true;
            axios
                .post(`/api/order/payment/complete/${this.$route.params.id}`)
                .then(res => {
                    this.getOrderDetail();
                    this.$toast.success(res.data.message, {
                        timeout: 5000
                    });
                    this.loading = false;
                })
                .catch(error => {
                    this.loading = false;
                    this.$toast.error(error.response.data.message, {
                        timeout: 5000
                    });
                });
        },
        nextSteep(status, id) {
            if (confirm("Are you sure to want to change status")) {
                axios
                    .patch(`/api/change/order/status/${id}`, {
                        status: status + 1
                    })
                    .then(res => {
                        this.getOrderDetail();
                        this.$toast.success(res.data.message, {
                            timeout: 2000
                        });
                    })
                    .catch(error => {
                        this.$toast.error(error.response.data.message, {
                            timeout: 2000
                        });
                    });
            }
        }
    },
    created() {
        this.getOrderDetail();
    }
};
</script>

<style scoped></style>
