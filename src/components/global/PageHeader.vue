<template>
    <header id="header">
        <div class="container">
            <h1>
                <a href="/">{{ site_name }}</a>
            </h1>

            <nav role="navigation">
                <ul>
                    <li>
                        <a href="#!">About</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script>
    export default {
        name: 'page-header',
        props: ['site_name']
    };
</script>

<style scoped lang="scss">
    h1 {
        margin-bottom: rem(40);
    }
</style>
