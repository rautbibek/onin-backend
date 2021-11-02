<template>
    <div>
        <b-link :items="items"></b-link>
        <v-stepper class="mx-3" v-model="e6" vertical>
            <v-stepper-step :complete="e6 > 1" step="1">
                Choose categegory
                <small>one at a time</small>
            </v-stepper-step>

            <v-stepper-content step="1" @click="e6 = 1">
                <v-card flat class="mb-12" height="auto">
                    <v-row class="pa-3">
                        <v-col cols="12">
                            <v-card-text
                                v-for="(category, index) in categories"
                                :key="index"
                            >
                                <h2 class="text-h6 mb-2">
                                    {{ index + 1 }}) {{ category.name }}
                                </h2>

                                <v-chip-group class="ml-5" v-model="cat" column>
                                    <v-chip
                                        :value="cat.id"
                                        v-for="(cat,
                                        index) in category.children"
                                        :key="index"
                                        label
                                        filter
                                    >
                                        {{ cat.name }}
                                    </v-chip>
                                </v-chip-group>
                            </v-card-text>
                        </v-col>
                    </v-row>
                </v-card>
                <v-btn color="primary" @click="e6 = 2">
                    NEXT
                </v-btn>
            </v-stepper-content>

            <v-stepper-step :complete="e6 > 2" step="2">
                Title And Description
            </v-stepper-step>

            <v-stepper-content step="2">
                <v-row class="pa-3">
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="formData.title"
                            label="Product Title"
                            outlined
                            dense
                            :rules="[
                                required('Product Title'),
                                maxLength('Product Title', 200)
                            ]"
                            counter="200"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-text-field
                            v-model="formData.category"
                            label="Search Text"
                            outlined
                            dense
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-textarea
                            counter
                            outlined
                            dense
                            label="Long Description"
                            :rules="[
                                required('Long Description'),
                                maxLength('Long Description', 10000)
                            ]"
                        ></v-textarea>
                    </v-col>
                    <v-col cols="12">
                        <v-textarea
                            counter
                            outlined
                            label="Short Description"
                            :rules="[
                                required('Short Description'),
                                maxLength('Short Description', 10000)
                            ]"
                        ></v-textarea>
                    </v-col>
                </v-row>
                <v-btn color="primary" @click="e6 = 3">
                    Continue
                </v-btn>
                <v-btn text @click="pre(1)">
                    back
                </v-btn>
            </v-stepper-content>

            <v-stepper-step :complete="e6 > 3" step="3">
                Product Attributes
            </v-stepper-step>

            <v-stepper-content step="3">
                <v-card class="mb-12" flat>
                    <UploadImages />
                </v-card>
                <v-btn color="primary" @click="e6 = 4">
                    Continue
                </v-btn>
                <v-btn text @click="pre(2)">
                    back
                </v-btn>
            </v-stepper-content>

            <v-stepper-step :complete="e6 > 4" step="4">
                Upload Product Images
            </v-stepper-step>

            <v-stepper-content step="4">
                <v-card class="mb-12" flat>
                    <UploadImages />
                </v-card>
                <v-btn color="primary" @click="e6 = 5">
                    Continue
                </v-btn>
                <v-btn text @click="e6 = 3">
                    back
                </v-btn>
            </v-stepper-content>

            <v-stepper-step step="5">
                SEO (Meta Title & Description)
            </v-stepper-step>
            <v-stepper-content step="5">
                <v-card flat class="mb-12" height="auto">
                    <v-row class="pa-3">
                        <v-col cols="12" sm="6">
                            <v-combobox
                                v-model="formData.meta_tags.tag_name"
                                label="Meta Keyword"
                                chips
                                multiple
                                dense
                                outlined
                            ></v-combobox>
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-text-field
                                v-model="formData.meta_title"
                                label="Meta Title"
                                outlined
                                dense
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-textarea
                                counter
                                outlined
                                v-model="formData.meta_description"
                                label="Meta Description"
                            ></v-textarea>
                        </v-col>
                    </v-row>
                </v-card>
                <v-btn color="primary" @click="saveProduct">
                    SAVE PRODUCT
                </v-btn>
                <v-btn text @click="pre(1)">
                    preview
                </v-btn>
            </v-stepper-content>
        </v-stepper>
    </div>
</template>
<script>
import UploadImages from "vue-upload-drop-images";
import { commonMixin } from "../../mixins/commonMixin";
import { validation } from "../../mixins/validationMixin";
export default {
    mixins: [commonMixin, validation],
    components: {
        UploadImages
    },
    data() {
        return {
            e6: 1,
            items: [
                {
                    text: "Dashboard",
                    disabled: false,
                    href: "/dashboard"
                },
                {
                    text: "Product",
                    disabled: true,
                    href: "/Product"
                }
            ],
            formData: {
                meta_tags: {}
            },
            cat: "",
            categories: []
        };
    },

    methods: {
        pre(data) {
            this.e6 = data;
        },
        saveProduct() {
            alert("finally your product is saved");
        },
        getCategory() {
            axios
                .get("/api/category")
                .then(res => {
                    this.categories = res.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    created() {
        this.getCategory();
    }
};
</script>
