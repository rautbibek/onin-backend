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
                // if (response.data.length > 0) {
                //     var ct = response.data;
                //     ct.data.forEach(category => {
                //         if (category.children.length > 0) {
                //             return ct;
                //         } else {
                //             category.children = null;
                //         }
                //     });

                // }

                commit("setCategories", response.data.data);
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
