require("./bootstrap");

window.Vue = require("vue").default;

import vuetify from "./plugins/vuetify";
import store from "./store";
import { router } from "./router";

Vue.component("b-link", require("./components/Blink.vue").default);
Vue.component("App", require("./App.vue").default);

const app = new Vue({
    vuetify,
    el: "#app",
    router,
    store
});
