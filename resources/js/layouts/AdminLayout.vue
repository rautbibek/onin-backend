<template>
    <v-app id="inspire">
        <v-app id="inspire">
            <v-navigation-drawer
                elevation="10"
                expand-on-hover
                floating
                dark
                style="background: rgb(75,14,65);
background: linear-gradient(24deg, rgba(75,14,65,1) 15%, rgba(49,14,162,1) 100%);"
                v-model="drawer"
                :clipped="$vuetify.breakpoint.lgAndUp"
                app
            >
                <!-- expand-on-hover -->
                <v-list dense color="danger">
                    <template v-for="item in items">
                        <v-row
                            v-if="item.heading"
                            :key="item.heading"
                            align="center"
                        >
                            <v-col cols="6">
                                <v-subheader v-if="item.heading">
                                    {{ item.heading }}
                                </v-subheader>
                            </v-col>
                            <v-col cols="6" class="text-center">
                                <a href="#!" class="body-2 black--text">EDIT</a>
                            </v-col>
                        </v-row>
                        <v-list-group
                            v-else-if="item.children"
                            :key="item.text"
                            v-model="item.model"
                            :prepend-icon="item.icon"
                        >
                            <template v-slot:activator>
                                <v-list-item-content>
                                    <v-list-item-title>
                                        {{ item.text }}
                                    </v-list-item-title>
                                </v-list-item-content>
                            </template>
                            <v-list-item
                                v-for="(child, i) in item.children"
                                :key="i"
                                :to="child.url"
                                link
                            >
                                <v-list-item-action v-if="child.icon">
                                    <v-icon>{{ child.icon }}</v-icon>
                                </v-list-item-action>
                                <v-list-item-content>
                                    <v-list-item-title>
                                        {{ child.text }}
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-group>
                        <v-list-item
                            v-else
                            :key="item.text"
                            link
                            :to="item.url"
                        >
                            <v-list-item-action>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>
                                    {{ item.text }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </template>
                </v-list>
            </v-navigation-drawer>

            <v-app-bar
                :clipped-left="$vuetify.breakpoint.lgAndUp"
                app
                style="background: rgb(2,0,36);
background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(121,9,38,0.804359243697479) 100%, rgba(0,212,255,1) 100%);"
                dark
            >
                <v-app-bar-nav-icon
                    @click.stop="drawer = !drawer"
                ></v-app-bar-nav-icon>
                <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
                    <span class="hidden-sm-and-down">{{ title }}</span>
                </v-toolbar-title>

                <v-spacer></v-spacer>
                <v-btn icon>
                    <v-icon>mdi-apps</v-icon>
                </v-btn>
                <notification></notification>

                <user-menu :mode="mode" @test="test"></user-menu>
            </v-app-bar>
            <v-main :class="mode ? '' : 'container-background'">
                <v-container fluid>
                    <router-view :key="$route.fullPath"></router-view>
                </v-container>
            </v-main>
        </v-app>
    </v-app>
</template>
<script>
import Notification from "../components/Notification.vue";
import UserMenu from "../components/UserMenu.vue";
export default {
    name: "AdminLayout",
    components: {
        Notification,
        UserMenu
    },
    data: () => ({
        title: process.env.MIX_APP_NAME,
        dialog: false,
        drawer: null,
        fav: true,
        menu: false,
        mode: false,

        items: [
            {
                icon: "dashboard",
                text: "Dashboard",
                url: "/dashboard"
            },

            {
                icon: "people",
                text: "Users",
                url: "/users"
            },
            {
                icon: "mdi-ballot",
                text: "Category",
                url: "/category"
            },
            {
                icon: "mdi-layers",
                text: "Brands",
                url: "/brand"
            },
            {
                icon: "mdi-call-split",
                text: "Collection",
                url: "/collection"
            },
            {
                icon: "inventory",
                text: "Product",
                url: "/product"
            },
            {
                icon: "event",
                text: "Order",
                url: "/order"
            },
            {
                icon: "mdi-content-copy",
                text: "Banner",
                url: "/banner"
            },
            {
                icon: "mdi-account-circle",
                text: "Setups",
                model: false,
                children: [
                    {
                        icon: "remove",
                        text: "Category Options",
                        url: "/category-option"
                    },
                    {
                        icon: "remove",
                        text: "Color Family",
                        url: "/color-family"
                    }
                ]
            },
            {
                icon: "mdi-map",
                text: "Addresses",
                model: false,
                children: [
                    {
                        icon: "remove",
                        text: "State",
                        url: "/state"
                    },
                    {
                        icon: "remove",
                        text: "District",
                        url: "/district"
                    },
                    {
                        icon: "remove",
                        text: "Cities",
                        url: "/city"
                    },
                    {
                        icon: "remove",
                        text: "LocalArea",
                        url: "/local-area"
                    }
                ]
            },
            // {
            //     icon: "mdi-home",
            //     text: "More",
            //     model: false,
            //     children: [
            //         { text: "Import" },
            //         { text: "Export" },
            //         { text: "Print" },
            //         { text: "Undo changes" },
            //         { text: "Other contacts" }
            //     ]
            // },
            { icon: "mdi-cog", text: "Settings", url: "/setting" },
            { icon: "mdi-message", text: "Sms Log", url: "/sms/log" },
            { icon: "mdi-help-circle", text: "Help" },
            { icon: "mdi-cellphone-link", text: "App downloads" },
            { icon: "mdi-keyboard", text: "Go to the old version" }
        ]
    }),

    methods: {
        changeMode() {
            this.$vuetify.theme.dark = this.mode;
        },
        test() {
            this.mode = !this.mode;
            this.changeMode();
        }
    }
};
</script>
<style scoped>
a {
    text-decoration: none;
}
.container-background {
    background-color: #f0f2f5;
}
.v-list-group--active .v-list-item__title {
    color: white;
}

.v-list-group--active .v-list-item__icon {
    color: white;
}
</style>
