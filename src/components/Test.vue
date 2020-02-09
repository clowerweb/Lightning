<template>
    <div id="test">
        <img src="@/assets/img/logo.png" height="400" width="400" alt="Vue logo"/>

        <p>
            <span :style="`color: ${color}`">
                <slot></slot>
            </span> module hot reloaded!
        </p>
    </div>
</template>

<script>
    export default {
        name: 'test',
        props: ['color']
    };
</script>

<style lang="scss" scoped>
    p {
        margin-bottom: 50px;

        span {
            color: $color-blue;
        }
    }
</style>
