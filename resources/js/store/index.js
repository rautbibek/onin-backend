import Vuex from "vuex";
import Vue from "vue";
import category from "./modules/category";
Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        category
    }
});
