<template>
    <div class="mb-2">
        <v-card flat>
            <v-breadcrumbs :items="items">
                <template v-slot:divider>
                    <v-icon>mdi-forward</v-icon>
                </template>
            </v-breadcrumbs>
        </v-card>
    </div>
</template>

<script>
export default {
    props: {
        items: {
            type: [Array, Object],
            required: false,
            default: function() {
                return [
                    {
                        text: "Dashboard",
                        disabled: false,
                        to: "/dashboard"
                    }
                ];
            }
        }
    }
};
</script>
