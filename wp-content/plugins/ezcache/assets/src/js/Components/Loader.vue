<template>
    <svg class="wpb-loader" viewBox="0 0 24 24" :style="`width: ${computedSize}; height: ${computedSize}; vertical-align: middle;`">
        <path :fill="color" d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
    </svg>
</template>
<style>
    .wpb-loader {
        animation: spin 0.65s linear infinite;
    }
    @keyframes spin {
        from {
            transform: rotate(0deg) translateZ(0);
        }
        to {
            transform: rotate(360deg) translateZ(0);
        }
    }
</style>
<script>
    export default {
        props: {
            size: {
                type: [String, Number],
                default: '1em'
            },
            color: {
                type: String,
                default: 'currentColor'
            }
        },
        computed: {
            computedSize() {
                // jQuery.isNumeric
                if( ! isNaN( this.size - parseFloat( this.size ) ) ) {
                    return this.size + 'px';
                }

                return this.size;
            }
        }
    }
</script>
