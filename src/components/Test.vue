<template>
    <div id="test">
        <img src="@/assets/img/PlayStation-Vue-Logo.jpg" height="627" width="1200" alt="PS Vue logo"/>

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
