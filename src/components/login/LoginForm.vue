<template>
    <div class="container">
        <h3 class="page-title">Sign In</h3>

        <p v-if="allow_registration === '1'">
            <strong>Don't have an account? <a href="/register">Register</a></strong>
        </p>

        <form method="post" action="/login">
            <div>
                <label for="email">Email address:</label>
                <input type="email" id="email" name="email" placeholder="Email address" :value="email" autofocus required />
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required />
            </div>

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember_me" :checked="remembered" /> Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Log in</button> <a href="/password/forgot">Forgot password?</a>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'login-form',
        props: ['allow_registration', 'email', 'remembered']
    };
</script>

<style lang="scss" scoped>
    button[type="submit"] {
        margin-right: rem(15);
    }
</style>
