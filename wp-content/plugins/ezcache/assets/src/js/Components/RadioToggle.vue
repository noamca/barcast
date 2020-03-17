<template>
    <div class="radio-con" role="radiogroup">
        <input ref="element" :id="id" type="hidden" :name="name" v-bind="$attrs" :value="selected">
        <button role="radio"
                type="button"
                class="button"
                :class="selected === val ? 'active button-success' : 'button-secondary'"
                v-for="(name, val) in items"
                :key="val"
                v-text="name"
                @click="e => emit(e, val)"
                :aria-checked="selected === val"></button>
    </div>
</template>

<script>
    export default {
        props: [ 'name', 'value', 'options', 'id' ],
        data() {
            return {
                selected: '',
                items: []
            }
        },
        mounted() {
            this.selected = this.value;
            this.items = JSON.parse(this.options);

            document.addEventListener('DOMContentLoaded', () => {
                let evt = new Event("change", { bubbles: true });
                this.$refs.element.dispatchEvent(evt);
            });
        },
        methods: {
            emit(e, val) {
                this.selected = val;
                this.$refs.element.value = val;
                let evt = new Event("change", { bubbles: true });
                this.$refs.element.dispatchEvent(evt);
            }
        }
    }
</script>

<style>
    .radio-con .button {
        box-shadow: none !important;
    }
    .radio-con .button:active,
    .radio-con .button:hover,
    .radio-con .button.active,
    .radio-con .button.active:active,
    .radio-con .button.active:hover {
        transform: none !important;
    }
    .radio-con .button:first-of-type {
        border-radius: 3px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    .radio-con .button + .button {
        border-radius: 0;
    }
    .radio-con .button:last-of-type {
        border-radius: 3px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    .radio-con .button:last-of-type:first-of-type {
        border-radius: 3px;
    }
    body.rtl .radio-con .button:first-of-type {
        border-radius: 3px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    body.rtl .radio-con .button + .button {
        border-radius: 0;
    }
    body.rtl .radio-con .button:last-of-type {
        border-radius: 3px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    body.rtl .radio-con .button:last-of-type:first-of-type {
        border-radius: 3px;
    }
</style>
