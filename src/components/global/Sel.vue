<template>
    <span class="select-wrapper">
        <select :name="name" @change="change">
            <option
                v-for="option in options"
                :value="getOption(option, 'id')"
                :selected="selected === true || selected === getOption(option, 'name')"
            >
                {{ getOption(option, 'name') }}
            </option>
        </select>
    </span>
</template>

<script>
    export default {
        name: 'sel',
        props: {
            name: {
                type: String,
                required: false
            },
            selected: {
                type: String|Boolean,
                required: false
            },
            options: {
                type: Array,
                required: true
            }
        },
        methods: {
            getOption(option, type) {
                return option[type] ? option[type] : option;
            },
            change(e) {
                this.$emit('change', e.target.value);
            }
        }
    };
</script>

<style scoped lang="scss">
    .select-wrapper {

        position: relative;

        &:after {
            display: block;
            color: $color-black;
            content: "\f0d7";
            font-family: "Font Awesome 5 Pro" !important;
            font-size: rem(16);
            font-weight: 900;
            height: rem(14);
            pointer-events: none;
            position: absolute;
            right: rem(5);
            top: rem(3);
            width: rem(10);
        }

        select {
            padding-right: rem(20);
        }

    }
</style>
