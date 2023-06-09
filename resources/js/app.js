require("./bootstrap");

window.Vue = require("vue").default;

import vuetify from "./plugins/vuetify";
import store from "./store";
import { router } from "./router";
import Vue from "vue";
import Toast from "vue-toastification";

// Import the CSS or use your own!
import "vue-toastification/dist/index.css";
import iosAlertView from "vue-ios-alertview";

Vue.use(iosAlertView);

const options = {
    // You can set your default options here
};
var numeral = require("numeral");
Vue.filter("formatNumber", function(value) {
    return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
});

Vue.use(Toast, options);
Vue.use(require("vue-moment"));
Vue.prototype.$user = window.user.admin;
Vue.prototype.$isLoggedIn = window.user.isLoggedIn;
Vue.component("b-link", require("./components/Blink.vue").default);
Vue.component("App", require("./App.vue").default);

const app = new Vue({
    vuetify,
    el: "#app",
    router,
    store
});
