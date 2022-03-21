<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        orderDetail
    </div>
</template>

<script>
export default {
    data() {
        return {
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
            ]
        };
    }
};
</script>

<style scoped></style>
