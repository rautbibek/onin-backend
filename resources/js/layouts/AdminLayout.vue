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
                <!-- <v-text-field
                    flat
                    solo-inverted
                    hide-details
                    prepend-inner-icon="mdi-magnify"
                    label="Search"
                    class="hidden-sm-and-down"
                ></v-text-field> -->
                <v-spacer></v-spacer>
                <v-btn icon>
                    <v-icon>mdi-apps</v-icon>
                </v-btn>
                <v-btn icon>
                    <v-icon>mdi-bell</v-icon>
                </v-btn>

                <v-menu
                    v-model="menu"
                    close-on-content-click
                    :nudge-width="200"
                    offset-y
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon large dark v-bind="attrs" v-on="on">
                            <v-avatar size="32px" item>
                                <v-img src="/src/logo.png" alt="Vuetify"></v-img
                            ></v-avatar>
                        </v-btn>
                    </template>

                    <v-card>
                        <v-list>
                            <v-list-item>
                                <v-list-item-avatar>
                                    <img src="/src/user.png" alt="John" />
                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title>{{
                                        $user.name
                                    }}</v-list-item-title>
                                    <v-list-item-subtitle
                                        >Admin</v-list-item-subtitle
                                    >
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>

                        <v-divider></v-divider>

                        <v-list>
                            <v-list-item>
                                <v-list-item-action>
                                    <v-switch
                                        @change="changeMode"
                                        v-model="mode"
                                        color="primary"
                                    ></v-switch>
                                </v-list-item-action>
                                <v-list-item-title>Dark Mode</v-list-item-title>
                            </v-list-item>
                            <v-list-item link>
                                <v-list-item-icon
                                    ><v-icon>mdi-lock</v-icon></v-list-item-icon
                                >
                                <v-list-item-title
                                    >Change Password</v-list-item-title
                                >
                            </v-list-item>

                            <v-list-item @click="logout">
                                <v-list-item-icon
                                    ><v-icon
                                        >mdi-logout</v-icon
                                    ></v-list-item-icon
                                >
                                <v-list-item-title>Logout</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-card>
                </v-menu>
            </v-app-bar>
            <v-main :class="mode ? '' : 'container-background'">
                <v-container fluid>
                    <router-view></router-view>
                </v-container>
            </v-main>
            <!-- <v-btn
                bottom
                color="primary"
                dark
                fab
                fixed
                right
                @click="dialog = !dialog"
            >
                <v-icon>mdi-email</v-icon>
            </v-btn> -->
            <!-- <v-dialog
        v-model="dialog"
        width="800px"
      >
        <v-card>
          <v-card-title class="grey darken-2">
            Create contact
          </v-card-title>
          <v-container>
            <v-row class="mx-2">
              <v-col
                class="align-center justify-space-between"
                cols="12"
              >
                <v-row
                  align="center"
                  class="mr-0"
                >
                  <v-avatar
                    size="40px"
                    class="mx-3"
                  >
                    <img
                      src="//ssl.gstatic.com/s2/oz/images/sge/grey_silhouette.png"
                      alt=""
                    >
                  </v-avatar>
                  <v-text-field
                    placeholder="Name"
                  ></v-text-field>
                </v-row>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  prepend-icon="mdi-account-card-details-outline"
                  placeholder="Company"
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  placeholder="Job title"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  prepend-icon="mdi-mail"
                  placeholder="Email"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  type="tel"
                  prepend-icon="mdi-phone"
                  placeholder="(000) 000 - 0000"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  prepend-icon="mdi-text"
                  placeholder="Notes"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
          <v-card-actions>
            <v-btn
              text
              color="primary"
            >More</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              text
              color="primary"
              @click="dialog = false"
            >Cancel</v-btn>
            <v-btn
              text
              @click="dialog = false"
            >Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog> -->

            <!-- menu block -->
        </v-app>
    </v-app>
</template>
<script>
export default {
    name: "AdminLayout",
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
            { icon: "mdi-message", text: "Send feedback" },
            { icon: "mdi-help-circle", text: "Help" },
            { icon: "mdi-cellphone-link", text: "App downloads" },
            { icon: "mdi-keyboard", text: "Go to the old version" }
        ]
    }),

    methods: {
        changeMode() {
            this.$vuetify.theme.dark = this.mode;
        },
        logout() {
            axios
                .post("/logout")
                .then(res => {
                    console.log(res.data);
                    window.location.href = "/";
                })
                .catch(err => {
                    console.log(err.response.data.errors);
                });
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
