import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
import Category from "../pages/Category.vue";
import Users from "../pages/Users.vue";
import Product from "../pages/product";
import NewProduct from "../pages/product/NewProduct";
import NotFound from "../pages/404";
import EditProduct from "../pages/product/EditProduct";
import Dashboard from "../pages/Dashboard";
import AdminLayout from "../layouts/AdminLayout.vue";
import ColorFamily from "../pages/attributes/ColorFamily.vue";
import SizeFamily from "../pages/attributes/SizeFamily.vue";
import Collection from "../pages/collection";
import Brand from "../pages/brand";
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
                path: "/users",
                name: "users",
                component: Users
            },
            {
                path: "/product",
                name: "Product",
                component: Product
            },
            {
                path: "product/add",
                name: "NewProduct",
                component: NewProduct
            },
            {
                path: "color-family",
                name: "ColorFamily",
                component: ColorFamily
            },
            {
                path: "size-family",
                name: "SizeFamily",
                component: ColorFamily
            },
            {
                path: "brand",
                name: "Brand",
                component: Brand
            },
            {
                path: "collection",
                name: "Collection",
                component: Collection
            },
            {
                path: "/product/edit/:id",
                name: "EditProduct",
                component: EditProduct
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
