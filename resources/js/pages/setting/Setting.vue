<template>
    <div>
        <b-link :items="breadcrumb"></b-link>
        setting page {{ name }}
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
                    text: "Setting",
                    disabled: true,
                    to: "/setting"
                }
            ]
        };
    },
    created() {
        axios
            .get("api/v1/product/2")
            .then(res => {
                console.log(res.data);
            })
            .catch();
    }
};
</script>

<style lang="scss" scoped></style>
