<template>
    <p class="text-center">
        <span>
            <slot></slot>
        </span>
    </p>
</template>

<script>
    export default {
        name: 'test',
        props: ['color']
    };
</script>

<style lang="scss" scoped>
    p {

        span {
            color: $color-blue;
        }

    }
</style>
