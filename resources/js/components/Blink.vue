<template>
    <div>
        <v-col>
            <v-card flat>
                <v-breadcrumbs :items="items">
                    <template v-slot:divider>
                        <v-icon>mdi-forward</v-icon>
                    </template>
                </v-breadcrumbs>
            </v-card>
        </v-col>
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
                        text: "Admin Home",
                        disabled: true,
                        href: "/dashboard"
                    }
                ];
            }
        }
    }
};
</script>
