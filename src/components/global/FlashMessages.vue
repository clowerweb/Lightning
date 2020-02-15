<template>
    <section v-if="messages" class="container">
        <div
            v-for="message in messages"
            class="alert"
            :class="`alert-${getType(message)}`"
            role="alert"
            v-html="getBody(message)"
        >
        </div>
    </section>
</template>

<script>
    export default {
        name: 'flash-messages',
        props: {
            messages: {
                type: Array|null,
                required: true
            },
            type: {
                type: String,
                required: false
            }
        },
        methods: {
            getType(message) {
                return this.type ? this.type : message.type ? message.type : 'warning';
            },

            getBody(message) {
                return message.body ? message.body : message;
            }
        }
    };
</script>

<style scoped lang="scss">
    .alert {

        border: rem(1) solid transparent;
        margin-bottom: rem(20);
        padding: rem(12) rem(20);

        &-success {
            background-color: $color-green-l;
            border-color: $color-green;
            color: $color-green;
        }

        &-info {
            background-color: $color-blue-l;
            border-color: $color-blue;
            color: $color-blue;
        }

        &-warning {
            background-color: $color-orange-l;
            border-color: $color-orange;
            color: $color-orange;
        }

        &-error {
            background-color: $color-red-l;
            border-color: $color-red;
            color: $color-red;
        }

    }
</style>
