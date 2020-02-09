<template>
    <div class="container">
        <h3 class="page-title">Register</h3>

        <div v-if="errors" class="errors alert alert-error">
            <strong>Please correct the following error(s):</strong>

            <ul>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
        </div>

        <p>
            <strong>Already have an account? <a href="/login">Login</a></strong>
        </p>

        <form method="post" action="/register" id="form-register">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Email" :value="email" required />
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Password" required />
            </div>
            <div id="password-error-container"></div>

            <button type="submit" class="btn btn-primary">Sign up</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'register-form',
        props: ['email', 'errors']
    };
</script>
