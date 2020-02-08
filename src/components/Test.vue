<template>
    <p>
        <span><slot></slot></span> module hot reloaded!!
    </p>
</template>

<script>
    export default {
        name: 'test',
        props: ['color']
    };
</script>

<style lang="scss" scoped>
    @import "../scss/main";

    p {
        margin-bottom: 50px;

        span {
            color: $color-blue;
        }
    }
</style>
