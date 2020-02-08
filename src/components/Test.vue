<template>
    <p>
        <span :style="`color: ${color}`"><slot></slot></span> module hot reloaded!!
    </p>
</template>

<script>
    export default {
        name: 'test',
        props: ['color']
    };
</script>

<style lang="scss" scoped>
    $color: green;

    p {
        margin-bottom: 50px;

        span {
            color: $color;
        }
    }
</style>
