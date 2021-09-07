import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
import Category from "../pages/Category.vue";
import Product from "../pages/Product.vue";
import NotFound from "../pages/404";
import Dashboard from "../pages/Dashboard";
import AdminLayout from "../layouts/AdminLayout.vue";
const routes = [
    {
        path: "*",
        name: "404",
        component: NotFound
    },

    {
        path: "/",
        name: "main",
        component: AdminLayout,
        children: [
            {
                path: "/",
                name: "Dashboard",
                component: Dashboard
            },
            {
                path: "dashboard",
                name: "Dashboard",
                component: Dashboard
            },
            {
                path: "/category",
                name: "category",
                component: Category
            },
            {
                path: "/product",
                name: "Product",
                component: Product
            }
        ]
    }
];

export const router = new VueRouter({
    routes,
    mode: "history"
    // scrollBehavior(to, from, savedPosition) {
    //     return {
    //         x: 0,
    //         y: 0
    //     }
    // }
});
