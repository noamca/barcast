<template>
    <div class="widget open" :class="{ 'no-top': !$slots.title }">
        <div v-if="$slots.title" class="widget-top">
            <div class="widget-title">
                <h3 :id="$id('title')">
                    <slot name="title"></slot>
                    <span class="in-widget-title"></span>
                </h3>
            </div>
        </div>

        <div class="widget-inside" :aria-describedby="$id('title')" :id="$id('panel')">
            <div class="widget-content">
                <div v-if="showSpinner" class="widget-loading-spinner">
                    <Loader size="64"></Loader>
                </div>
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from "./Loader";
    export default {
        components: {Loader},
        props: {
            showSpinner: {
                type: Boolean,
                default: false
            }
        }
    };
</script>
