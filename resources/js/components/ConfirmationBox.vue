<template>
    <div>
        <v-dialog v-model="confirm" persistent max-width="300px">
            <v-card>
                <v-card-title class="text-h5">
                    Confirm
                </v-card-title>

                <v-card-text>Are you sure ?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>

                    <slot name="cancel"></slot>
                    &nbsp;
                    <slot name="ok"></slot>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
export default {
    props: {
        confirm: {
            type: Boolean,
            default: false
        }
    }
};
</script>
