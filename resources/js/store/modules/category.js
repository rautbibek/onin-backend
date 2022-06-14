import axios from "axios";
const state = {
    cat: []
};
const getters = {
    fetAllCategories: state => state.cat
};
const actions = {
    getCategories({ commit }) {
        axios
            .get(`/api/category/parent`)
            .then(response => {
                commit("setCategories", response.data.data);
            })
            .catch(error => {});
    },

    addCategories({ commit }) {
        axios
            .post(`api/category`, formData)
            .then(response => {
                commit("setCategories", response.data.data);
            })
            .catch(error => {});
    }
};
const mutations = {
    setCategories: (state, cat) => (state.cat = cat)
    //newCategory: (state, cat) => (state.cat = cat)
};

export default {
    state,
    getters,
    actions,
    mutations
};
