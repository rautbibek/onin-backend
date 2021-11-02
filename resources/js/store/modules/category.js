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
            .get(`api/v1/category`)
            .then(response => {
                commit("setCategories", response.data);
                //console.log(response.data);
            })
            .catch(error => {
                console.log(error.response.data.errors);
            });
    },
    addCategories({ commit }) {
        axios
            .post(`api/category`, formData)
            .then(response => {
                commit("setCategories", response.data.data);
            })
            .catch(error => {
                console.log(error.response.data.errors);
            });
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
