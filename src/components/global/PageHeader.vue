<template>
    <header id="header">
        <div class="container">
            <h1>
                <a href="/">{{ site_name }}</a>
            </h1>

            <h2 v-if="site_tagline">{{ site_tagline }}</h2>

            <nav role="navigation" class="text-right">
                <ul>
                    <li v-if="user_exists">
                        <a href="/logout">Log Out</a>
                    </li>
                    <li v-else>
                        <a href="/login">Log In</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script>
    export default {
        name: 'page-header',
        props: {
            site_name: {
                type: String,
                required: true
            },
            site_tagline: {
                type: String,
                required: false
            },
            user: {
                type: Object,
                required: true,
                default: {}
            }
        },
        computed: {
            user_exists: function() {
                return Object.keys(this.user).length > 0;
            }
        }
    };
</script>

<style scoped lang="scss">
    #header {

        background-color: $color-white;
        box-shadow: 0 0 rem(10) rgba(0, 0, 0, 1);
        left: 0;
        max-width: 100%;
        padding: rem(15) 0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 9999;

        > .container {

            align-items: center;
            display: flex;

            > h2 {
                align-self: flex-end;
                color: $color-gray;
                font-size: rem(20);
                margin-left: rem(15);
                padding-bottom: rem(2);
            }

            > nav {
                flex: 1;
                max-width: 100%;
                width: 100%;
            }

        }

    }
</style>
