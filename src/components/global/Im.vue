<template>
    <img :src="source"/>
</template>

<script>
    export default {
        name: 'im',
        props: {
            src: {
                type: String,
                required: true
            }
        },
        computed: {
            source() {
                return require('@/assets/img/' + this.src);
            }
        }
    };
</script>
