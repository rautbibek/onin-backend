<template>
    <div>
        <router-view></router-view>
    </div>
</template>
<script>
export default {
    name: "App",
    data() {
        return {
            items: [
                {
                    text: "Dashboard",
                    disabled: false,
                    href: "breadcrumbs_dashboard"
                },
                {
                    text: "Link 1",
                    disabled: false,
                    href: "breadcrumbs_link_1"
                },
                {
                    text: "Link 2",
                    disabled: true,
                    href: "breadcrumbs_link_2"
                }
            ]
        };
    },
    created() {
        if (window.user.isLoggedIn) {
            window.href = "/dashboard";
        }
    }
};
</script>
