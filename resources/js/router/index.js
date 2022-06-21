import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
import Category from "../pages/Category.vue";
import Test from "../pages/test.vue";
import Users from "../pages/user";
import UserDetail from "../pages/user/UserDetail";
import Product from "../pages/product";
import NewProduct from "../pages/product/NewProduct";
import NotFound from "../pages/404";
import EditProduct from "../pages/product/EditProduct";
import Dashboard from "../pages/Dashboard";
import AdminLayout from "../layouts/AdminLayout.vue";
import ColorFamily from "../pages/attributes/ColorFamily.vue";
import CategoryOption from "../pages/attributes/CategoryOptions.vue";
import Setting from "../pages/setting/Setting.vue";
import Collection from "../pages/collection";
import District from "../pages/address/District.vue";
import State from "../pages/address/State.vue";
import City from "../pages/address/City.vue";
import LocalArea from "../pages/address/LocalArea.vue";
import Brand from "../pages/brand";
import Order from "../pages/order/order.vue";
import CollectionProduct from "../pages/collection/CollectionProduct.vue";
import OrderDetail from "../pages/order/OrderDetail.vue";
import Banner from "../pages/banner";
import SmsLog from "../pages/sms_log";
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
            },
            {
                path: "/setting",
                name: "Setting",
                component: Setting
            },
            {
                path: "/state",
                name: "State",
                component: State
            },
            {
                path: "/district",
                name: "District",
                component: District
            },
            {
                path: "/city",
                name: "City",
                component: City
            },
            {
                path: "/local-area",
                name: "LocalArea",
                component: LocalArea
            },
            {
                path: "/order",
                name: "Order",
                component: Order
            },
            {
                path: "/order/detail/:id",
                name: "OrderDetail",
                component: OrderDetail
            },
            {
                path: "/sms/log",
                name: "SmsLog",
                component: SmsLog
            },
            {
                path: "/collection/product/:id",
                name: "CollectionProduct",
                component: CollectionProduct
            },
            {
                path: "/user/:id",
                name: "UserDetail",
                component: UserDetail
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
