<template>
    <div class="container">
        <h3 class="page-title">Password reset request</h3>

        <form method="post" action="/password/request-reset">
            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" id="email" class="form-control" name="email" placeholder="Email address" autofocus required />
            </div>

            <button type="submit" class="btn btn-primary">Send password reset</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'forgot-form'
    };
</script>
