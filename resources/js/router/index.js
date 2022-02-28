import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
import Category from "../pages/Category.vue";
import Test from "../pages/test.vue";
import Users from "../pages/Users.vue";
import Product from "../pages/product";
import NewProduct from "../pages/product/NewProduct";
import NotFound from "../pages/404";
import EditProduct from "../pages/product/EditProduct";
import Dashboard from "../pages/Dashboard";
import AdminLayout from "../layouts/AdminLayout.vue";
import ColorFamily from "../pages/attributes/ColorFamily.vue";
import CategoryOption from "../pages/attributes/CategoryOptions.vue";
import Collection from "../pages/collection";
import Brand from "../pages/brand";
import Banner from "../pages/banner";
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
                path: "test",
                name: "Test",
                component: Test
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
                path: "category-option",
                name: "CategoryOption",
                component: CategoryOption
            },
            {
                path: "brand",
                name: "Brand",
                component: Brand
            },
            {
                path: "banner",
                name: "Banner",
                component: Banner
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
