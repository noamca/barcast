<template>
    <div class="switch" :class="[size, type]">
        <input ref="checkbox" type="checkbox" :id="id || $id('switch')" @change="$emit('change', $event.target.checked)" :checked="checked" v-bind="$attrs" />
        <label :for="id || $id('switch')" aria-hidden="true">
            <span v-text="textOn || wpb.trans.enabled"></span>
            <span v-text="textOff || wpb.trans.disabled"></span>
        </label>
    </div>
</template>

<script>
    export default {
        props: {
            name: String,
            textOn: String,
            textOff: String,
            id: String,
            size: String,
            type: String,
            checked: Boolean
        },
        model: {
            prop: 'checked',
            event: 'change'
        }
    }
</script>

<style>
    .switch {
        display: inline-block;
    }
    .switch input[type="checkbox"] {
        position: absolute;
        top: auto;
        overflow: hidden;
        clip: rect(1px, 1px, 1px, 1px);
        width: 1px;
        height: 1px;
        white-space: nowrap;
    }
    .switch input[type="checkbox"] + label {
        display: block;
        position: relative;
        background: #e5e5e5;
        -webkit-border-radius: 20px;
        border-radius: 20px;
        min-width: 40px;
        height: 32px;
        padding: 6px;
        transition: background 0.3s ease;
        user-select: none;
        cursor: pointer;
    }
    .switch input[type="checkbox"][disabled] + label {
        opacity: 0.5;
    }
    .switch input[type="checkbox"] + label:after {
        content: '';
        position: absolute;
        left: 3px;
        top: 4px;
        background: white;
        border-radius: 20px;
        width: 24px;
        height: 24px;
        transition: all 0.1s ease;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
    }
    .switch input[type="checkbox"]:active + label,
    .switch input[type="checkbox"]:focus + label {
        box-shadow: 0 0 0 1px #5b9dd9, 0 0 2px 1px rgba(30,140,190,.8);
        outline: 1px solid transparent;
    }
    .switch input[type="checkbox"]:active + label::after,
    .switch input[type="checkbox"]:focus + label::after {
        background: radial-gradient(#d6d6d6, #d6d6d6 20%, #fff 24%, #fff);
    }
    .switch input[type="checkbox"] + label span {
        display: block;
        padding: 0 8px;
        height: 0;
        color: #282c2f;
        font-family: sans-serif;
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        line-height: 20px;
        transition: opacity 0.3s ease;
    }
    .switch input[type="checkbox"] + label span:first-child {
        padding-right: 26px;
        opacity: 0;
        visibility: hidden;
    }
    .switch input[type="checkbox"] + label span:last-child {
        padding-left: 26px;
    }
    .switch input[type="checkbox"]:checked + label {
        background: #23d160;
    }
    .switch input[type="checkbox"]:checked + label span {
        color: #003116;
    }
    .switch.danger input[type="checkbox"]:checked + label {
        background: #C94F26;
    }
    .switch.danger input[type="checkbox"]:checked + label span {
        color: #fff;
    }
    .switch.warning input[type="checkbox"]:checked + label {
        background: #ffdd57;
    }
    .switch.warning input[type="checkbox"]:checked + label span {
        color: rgba(0, 0, 0, 0.7019607843137254);
    }
    .switch input[type="checkbox"]:checked + label span:first-child {
        opacity: 1;
        visibility: visible;
    }
    .switch input[type="checkbox"]:checked + label span:last-child {
        opacity: 0;
        visibility: hidden;
    }
    .switch input[type="checkbox"]:checked + label:after {
        left: 100%;
        margin-left: -27px;
    }

    .switch.small  input[type="checkbox"] + label {
        height: 22px;
        padding: 2px 1px 0 1px;
    }
    .switch.small  input[type="checkbox"] + label:after {
        left: 5px;
        top: 5px;
        width: 14px;
        height: 14px;
    }
    .switch.small input[type="checkbox"]:checked + label:after {
        left: 100%;
        margin-left: -19px;
    }
    .switch.small input[type="checkbox"] + label span:first-child {
        padding-right: 22px;
    }
    .switch.small input[type="checkbox"] + label span:last-child {
        padding-left: 22px;
    }
</style>
