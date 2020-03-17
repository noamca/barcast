<template>
    <button type="button" @click.prevent="doAjax" :disabled="isBusy" :class="{ 'wpb-ajax-busy': isBusy }">
        <Loader v-if="isBusy"></Loader>
        <slot></slot>
    </button>
</template>

<script>
    import Loader from "./Loader";
    import API from "../Utilities/Api";

    export default {
        components: {Loader},
        props: {
            endpoint: {
                type: String,
                required: true
            },
            method: {
                type: String,
                default: 'post'
            },
            data: {
                type: Object,
                default: () => { return {}; }
            }
        },
        data() {
            return {
                isBusy: false
            };
        },
        computed: {
            className() {
                return this.props.class;
            }
        },
        methods: {
            async doAjax() {
                this.isBusy = true;
                const method = this.method.toLowerCase().trim();
                const endpoint = this.endpoint.trim();

                try {
                    let response = await API[method](endpoint, this.data);
                    this.$emit('success', response.data);
                } catch (error) {
                    console.error(error);
                    this.$emit('error', error);
                }

                this.$emit('complete');
                this.isBusy = false;
            }
        }
    };
</script>

<style>
    button.wpb-ajax-busy svg:not(.wpb-loader) {
        display: none !important;
    }
</style>
