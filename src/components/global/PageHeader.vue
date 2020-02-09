<template>
    <header id="header">
        <div class="container">
            <h1>
                <a href="/">{{ site_name }}</a>
            </h1>

            <nav role="navigation">
                <ul>
                    <li v-if="user">
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
        props: ['site_name', 'user']
    };
</script>

<style scoped lang="scss">
    #header {
        margin-bottom: rem(40);
    }
</style>
