<template>
    <img :src="source"/>
</template>

<script>
    export default {
        name: 'im',
        props: ['src'],
        computed: {
            source() {
                return require('@/assets/img/' + this.src);
            }
        }
    };
</script>
