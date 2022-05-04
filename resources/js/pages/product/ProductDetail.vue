<template>
    <v-dialog v-model="product_detail_dialog" scrollable max-width="600px">
        <v-card>
            <slot name="toolbar"></slot>

            <v-card-text>
                <div v-if="detail.variant.length > 0">
                    <v-card-title>Product Variants</v-card-title>
                    <v-simple-table fixed-header>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th class="text-left">
                                        Color
                                    </th>
                                    <th class="text-left">
                                        Quantity
                                    </th>
                                    <th class="text-left">
                                        SKU
                                    </th>
                                    <th class="text-left">
                                        Price
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in detail.variant"
                                    :key="item.name"
                                >
                                    <td>
                                        <span v-if="item.color">{{
                                            item.color
                                        }}</span>
                                        <span v-else>N/A</span>
                                    </td>
                                    <td>{{ item.quantity }}</td>
                                    <td>{{ item.sku }}</td>
                                    <td>
                                        Rs.{{
                                            Intl.NumberFormat().format(
                                                item.price
                                            )
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </div>
                <hr />

                <div v-if="detail.option_values.length > 0">
                    <v-card-title>Product Features</v-card-title>
                    <v-simple-table fixed-header>
                        <template v-slot:default>
                            <tbody>
                                <tr
                                    v-for="item in detail.option_values"
                                    :key="item.name"
                                >
                                    <td style="text-transform:uppercase">
                                        {{ item.option.split("_").join(" ") }}
                                    </td>
                                    <td>
                                        {{ item.option_value }}
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    props: {
        product_detail_dialog: {
            type: Boolean,
            default: false
        },
        detail: {
            type: Object,
            default: {}
        }
    }
};
</script>
